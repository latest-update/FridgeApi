<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Fridge;
use App\Models\Warehouse;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index (Fridge $fridge): JsonResponse
    {
        return ShortResponse::json($fridge->warehouse);
    }

    public function indexIncludeInfo (Fridge $fridge): JsonResponse
    {
        return ShortResponse::json($fridge->products()->get());
    }

    public function create (Request $request): JsonResponse
    {

        $fridge = Fridge::find($request->fridge_id);

        if ( $fridge == null )
            return ShortResponse::errorMessage('Fridge not found');

        if( $fridge->tfid != $request->tfid)
            return ShortResponse::errorMessage('Invalid fridge address (TFID)', 400);


        $data = $request->validate([
            'data.*.product_id' => 'required|integer|exists:App\Models\Product,id',
            'data.*.count' => 'required|integer'
        ]);

        $data = collect($data['data'])->map(function ($item) use ($fridge) {
            return [
                'product_id' => $item['product_id'],
                'fridge_id' => $fridge->id,
                'count' => $item['count']
            ];
        });

        if ( $fridge->mode_id != 2)
            return ShortResponse::json(['message' => 'Fridge isn\'t in maintenance mode']);

        try {
            $data = Warehouse::upsert($data->toArray(), ['product_id', 'fridge_id'], ['count']);
            $fridge->warehouse()->where('count', '<=', 0)->delete();
            return ShortResponse::json(['message' => 'Fridge warehouse was updated']);
        } catch (QueryException $exception) {
            return ShortResponse::errorMessage($exception, 409);
        }
    }


    public function fresh (Fridge $fridge): JsonResponse
    {
        $fridge->warehouse()->delete();
        return ShortResponse::json(['message' => 'Fridge warehouse was cleaned']);
    }
}
