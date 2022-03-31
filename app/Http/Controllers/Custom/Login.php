<?php


namespace App\Http\Controllers\Custom;


use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken as UserToken;

class Login
{
    public static function in(User $user): JsonResponse
    {
        switch ($user->role_id) {
            case 1:
                $role = 'role-user';
                break;
            case 2:
                $role = 'role-admin';
                break;
            case 3:
                $role = 'role-employer';
                break;
            default:
                $role = 'role-user';
        }

        UserToken::where('tokenable_id', $user->id)->delete();
        $response['token'] = $user->createToken('old', [ $role ])->plainTextToken;
        $response['info'] = $user;

        $user->remember_token = $response['token'];
        $user->save();

        return ShortResponse::json($response, 200);
    }
}
