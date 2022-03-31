<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Fridge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FridgeController extends Controller
{
    public function index (): JsonResponse
    {
        return ShortResponse::json(Fridge::all());
    }

    public function withLocation(): JsonResponse
    {
        return ShortResponse::json(Fridge::with(['location', 'mode'])->get());
    }

    public function fridgeById (Fridge $fridge): JsonResponse
    {
        return ShortResponse::json($fridge);
    }

    public function byLocationId (Fridge $fridge): JsonResponse
    {
        return ShortResponse::json($fridge);
    }

    public function create (Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:1|max:255',
            'location_id' => 'required|integer',
            'mode_id' => 'required|integer',
            'token' => 'required|string|min:1|max:255'
        ]);
        $data = Fridge::create($data);

        return ShortResponse::json(['message' => 'Fridge was created', 'created_id' => $data->id], 201);
    }

    public function update (Request $request, Fridge $fridge): JsonResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|min:1|max:255',
            'location_id' => 'nullable|integer',
            'mode_id' => 'nullable|integer',
            'token' => 'nullable|string|min:1|max:255'
        ]);

        $fridge->update($data);
        return ShortResponse::json(['message' => 'Fridge was updated']);
    }

    public function delete (Request $request, Fridge $fridge): JsonResponse
    {
        $fridge->delete();
        return ShortResponse::json(['message' => 'Fridge was deleted']);
    }
}
