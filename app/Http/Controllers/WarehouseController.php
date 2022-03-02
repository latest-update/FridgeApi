<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Fridge;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index (Fridge $fridge): JsonResponse
    {
        return ShortResponse::json(true, 'Fridge warehouse are retrieved...', $fridge->warehouse );
    }

    public function indexIncludeInfo (Fridge $fridge): JsonResponse
    {
        return ShortResponse::json(true, 'Fridge warehouse retrieved...', $fridge->products()->get() );
    }

    public function create (Request $request): JsonResponse
    {
        $data = $request->validate([
            '*.product_id' => 'required|integer',
            '*.fridge_id' => 'required|integer',
            '*.count' => 'required|integer'
        ]);

        $fridge_id = $data[0]['fridge_id'];

        $data = Warehouse::upsert($data, ['product_id', 'fridge_id'], ['count']);
        Fridge::find($fridge_id)->warehouse()->where('count', '<=', 0)->delete();
        return ShortResponse::json(true, 'Have done', $data);
    }


    public function fresh (Fridge $fridge): JsonResponse
    {
        return ShortResponse::json(true, 'Fridge warehouse was cleaned', $data->warehouse()->delete() );
    }
}
