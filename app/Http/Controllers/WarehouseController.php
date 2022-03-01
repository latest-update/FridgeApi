<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Fridge;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index (int $id): JsonResponse
    {
        return ShortResponse::json(true, 'Product are retrieved...', Fridge::find($id)->warehouse );
    }

    public function indexIncludeInfo (int $id): JsonResponse
    {
        return ShortResponse::json(true, 'Product are retrieved...', Fridge::find($id)->products()->get() );
    }

    public function create (Request $request): JsonResponse
    {
        $data = $request->validate([
            '*.product_id' => 'required|integer',
            '*.fridge_id' => 'required|integer',
            '*.count' => 'required|integer'
        ]);

        $data = Warehouse::upsert($data, ['product_id', 'fridge_id'], ['count']);
        return ShortResponse::json(true, 'Product are retrieved...', $data);
    }
}
