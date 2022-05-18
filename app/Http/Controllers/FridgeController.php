<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Fridge;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

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

    public function tfid (Fridge $fridge): JsonResponse
    {
        return ShortResponse::json([ 'tfid' => $fridge->tfid ]);
    }

    public function idByTfid (Request $request)
    {
        try {
            $fridge_id = Fridge::select('id')->where('tfid', $request->tfid)->get()[0]->toArray();
        } catch (\ErrorException $exception) {
            return ShortResponse::errorMessage('Invalid TFID or QR causes', 400);
        }

        $token = $request->user_token;
        $token = PersonalAccessToken::findToken($token);
        if ( $token == null )
            return ShortResponse::errorMessage('Invalid User Token', 401);

        $user = $token->tokenable;

        return ShortResponse::json(['fridge_id' => $fridge_id['id'], 'user_id' => $user->id ]);
    }

    public function create (Request $request): JsonResponse
    {
        $location = Location::find($request->location_id);
        $data = $request->validate([
            'name' => 'required|string|min:1|max:255',
//            'location_id' => 'required|integer|exists:App\Models\Location,id',
            'mode_id' => 'required|integer'
        ]);
        if ( Location::find($data['location_id'])->fridge != null )
            return ShortResponse::errorMessage('This location associated with other fridge', 409);

        $data['tfid'] = Str::random(64);
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
