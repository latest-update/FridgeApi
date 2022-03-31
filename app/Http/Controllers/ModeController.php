<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Mode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ModeController extends Controller
{
    public function index (): JsonResponse
    {
        return ShortResponse::json(Mode::all());
    }

    public function allFridgeByMode (int $id): JsonResponse
    {
        return ShortResponse::json(Mode::find($id)->fridges);
    }

    public function create (Request $request): JsonResponse
    {
        $data = $request->validate([
            'mode' => 'required|string|max:255|unique:modes,mode'
        ]);
        $mode = Mode::create($data);
        return ShortResponse::json(['message' => 'Mode was created', 'created_id' => $mode->id], 201);
    }

    public function update (Request $request, Mode $mode): JsonResponse
    {
        $data = $request->validate([
            'city' => 'nullable|string|max:255'
        ]);

        $mode->update($data);
        return ShortResponse::json(['message' => 'Mode was updated']);
    }

    public function delete (Request $request, Mode $mode): JsonResponse
    {
        if( App::environment('production') )
            return ShortResponse::errorMessage('Can\'t delete in production');

        $mode->delete();
        return ShortResponse::json(['message' => 'Mode was deleted']);
    }

}
