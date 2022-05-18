<?php


namespace App\Http\Controllers\CoR\Handlers;


use App\Http\Controllers\CoR\Middleware;
use App\Http\Controllers\Custom\ShortResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCheckHandler extends Middleware
{
    private ?User $user = null;

    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    public function handle(Request $request)
    {
        if ( $this->user == null )
            return ShortResponse::errorMessage('User not found');

        return $this->next($request);
    }
}
