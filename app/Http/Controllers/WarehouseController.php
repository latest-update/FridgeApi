<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Fridge;
use App\Models\Warehouse;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $data = $request->validate([
            '*.product_id' => 'required|integer',
            '*.fridge_id' => 'required|integer',
            '*.count' => 'required|integer'
        ]);

        $fridge_id = $data[0]['fridge_id'];
        $fridge = Fridge::find($fridge_id);

        if ( $fridge->mode_id != 2)
            return ShortResponse::json(['message' => 'Fridge isn\'t in maintenance mode']);

        try {
            $data = Warehouse::upsert($data, ['product_id', 'fridge_id'], ['count']);
            $fridge->warehouse()->where('count', '<=', 0)->delete();
            return ShortResponse::json(['message' => 'Fridge warehouse was updated']);
        } catch (QueryException $exception) {
            return ShortResponse::errorMessage('Something goes wrong', 409);
        }
    }


    public function fresh (Fridge $fridge): JsonResponse
    {
        $fridge->warehouse()->delete();
        return ShortResponse::json(['message' => 'Fridge warehouse was cleaned']);
    }
}
