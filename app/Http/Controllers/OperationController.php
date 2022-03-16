<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Fridge;
use App\Models\Operation;
use App\Models\Product;
use App\Models\Purchased_product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OperationController extends Controller
{
    public function index (Request $request): JsonResponse
    {
        if ( $request->user()->tokenCan('role-admin') )
            return ShortResponse::json(true, 'All users operation retrieved...', Operation::all());

        return ShortResponse::json(true, 'All operations are retrieved...', $request->user()->operations()->with('fridge')->get() );
    }

    public function byUserId (Request $request, User $user): JsonResponse
    {
        if( $request->user()->id != $user->id and !$request->user()->tokenCan('role-admin') )
            return ShortResponse::json(false, 'Trying to get other user information', [], 403);

        return ShortResponse::json(true, 'All operations by user are retrieved', $user->operations()->get());
    }

    public function operationDetail (Request $request, Operation $operation): JsonResponse
    {
        if( $request->user()->id != $operation->user_id and !$request->user()->tokenCan('role-admin') )
            return ShortResponse::json(false, 'Trying to change other user information', [], 403);

        return ShortResponse::json(true, 'All operations include product info are retrieved', $operation->products()->get() );
    }

    public function createOperation (Request $request, Fridge $fridge): JsonResponse
    {
        /*
         *
         * Computing fridge warehouse and difference
         *
         */
        $purchase = $request->validate([
            '*.product_id' => 'required|integer',
            '*.fridge_id' => ['required', 'integer', Rule::in([$fridge->id])],
            '*.count' => 'required|integer'
        ]);
        $purchase = collect($purchase);
        $fridgeProducts = $purchase->map(fn($item) => $item['product_id']);
        $products = Product::select('id', 'cost')->whereIn('id', $fridgeProducts)->get()->keyBy('id');
        $fridgeProducts = $fridge->warehouse()->whereIn('product_id', $fridgeProducts)->get()->keyBy('product_id');

        $purchase = $purchase->keyBy('product_id');

        $remainInFridge = $fridgeProducts->map(function ($item, $key) use ($purchase) {
            return [
                'product_id' => $item['product_id'],
                'fridge_id' => $item['fridge_id'],
                'count' => $item['count'] - $purchase[$key]['count']
            ];
        });

        /*
         *
         * Save in operation, save in purchased products
         *      Delete user_id from fridge,
         *          Update warehouse,
         *              Response to user, fridge
         *
         *
         */
        $purchase_price = $purchase->map(function ($item, $key) use ($products) {
            return $item['count'] * $products[$key]['cost'];
        })->sum();

        $operation = Operation::create([
            'user_id' => $fridge->userId_open,
            'fridge_id' => $fridge->id,
            'time' => now()->format('Y-m-d H:i:s'),
            'purchased_price' => $purchase_price
        ]);

        $purchase = $purchase->map(function ($item, $key) use ($operation) {
            return [
                'product_id' => $item['product_id'],
                'operation_id' => $operation->id,
                'purchased_count' => $item['count']
            ];
        });
        Purchased_product::upsert($purchase->toArray(), []);

        $fridge->userId_open = null;
        $fridge->save();

        $data = Warehouse::upsert($remainInFridge->toArray(), ['product_id', 'fridge_id'], ['count']);
        $fridge->warehouse()->where('count', '<=', 0)->delete();

        return ShortResponse::json(true, 'Ok', []);
    }
}
