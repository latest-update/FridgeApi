<?php

namespace App\Http\Controllers;

use App\Events\TFID;
use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Fridge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class QRController extends Controller
{

    public function getQR(Fridge $fridge): JsonResponse
    {
        $fridge->tfid = Str::random(16);
        $fridge->save();

        event(new TFID($fridge->tfid, $fridge->id, null));

        return ShortResponse::json(['tfid' => $fridge->tfid]);
    }

    public function scanQR(Request $request, Fridge $fridge)
    {
        $token = $request->user_token;
        $token = PersonalAccessToken::findToken($token);
        if ( $token == null )
            return ShortResponse::errorMessage('Invalid User Token', 401);

        $user = $token->tokenable;

        $fridge->tfid = Str::random(16);
        $fridge->save();

        event(new TFID($fridge->tfid, $fridge->id, $user->id));

        return ShortResponse::json(['message' => 'updated']);
    }
}
