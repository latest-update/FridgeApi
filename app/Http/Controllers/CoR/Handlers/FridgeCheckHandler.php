<?php


namespace App\Http\Controllers\CoR\Handlers;


use App\Http\Controllers\CoR\Middleware;
use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Fridge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FridgeCheckHandler extends Middleware
{
    private ?Fridge $fridge;

    public function __construct(?Fridge $fridge)
    {
        $this->fridge = $fridge;
    }

    public function handle(Request $request)
    {
        if ( $this->fridge == null )
            return ShortResponse::errorMessage('Fridge not found');
        if ( $this->fridge->mode_id != 1 )
            return ShortResponse::errorMessage('Fridge not active for purchase');
        if ( $this->fridge->tfid != $request->tfid )
            return ShortResponse::errorMessage('Invalid TemporaryFridgeID');

        return $this->next($request);
    }

}
