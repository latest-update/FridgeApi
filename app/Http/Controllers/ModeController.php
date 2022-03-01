<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Mode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModeController extends Controller
{
    public function index (): JsonResponse
    {
        return ShortResponse::json(true, 'Modes are retrieved...', Mode::all());
    }

    public function allFridgeByMode (int $id): JsonResponse
    {
        return ShortResponse::json(true, 'All fridges by mode are retrieved...', Mode::find($id)->fridges);
    }

    public function create (Request $request): JsonResponse
    {
        $data = $request->validate([
            'mode' => 'required|string|max:255|unique:modes,mode'
        ]);

        return ShortResponse::json(true, 'Mode created!', Mode::create($data), 201);
    }

    public function update (Request $request, Mode $mode): JsonResponse
    {
        $data = $request->validate([
            'city' => 'nullable|string|max:255'
        ]);

        $mode->update($data);
        return ShortResponse::json(true, 'Mode updated', $mode);
    }

    public function delete (Request $request, Mode $mode): JsonResponse
    {
        $mode->delete();
        return ShortResponse::json(true, 'Mode was deleted', $mode);
    }

}
