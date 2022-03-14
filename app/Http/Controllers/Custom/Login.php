<?php


namespace App\Http\Controllers\Custom;


use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken as userToken;

class Login
{
    public static function in(User $user): JsonResponse
    {
//        if ( $user->getRememberToken() != null )
//            return ShortResponse::json(true, 'User login successfully!', [
//                'token' => $user->getRememberToken(),
//                'info' => $user
//            ], 201);


        switch ($user->role_id) {
            case 1:
                $role = 'role-user';
                break;
            case 2:
                $role = 'role-admin';
                break;
            case 3:
                $role = 'role-employeer';
                break;
        }

        userToken::where('tokenable_id', $user->id)->delete();
        $response['token'] = $user->createToken($user->login, [$role])->plainTextToken;
        $response['info'] = $user;

        $user->remember_token = $response['token'];
        $user->save();

        return ShortResponse::json(true, 'User login successfully!', $response, 200);
    }
}
