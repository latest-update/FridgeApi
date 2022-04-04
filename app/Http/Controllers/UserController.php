<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\Login;
use App\Http\Controllers\Custom\ShortResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function users (Request $request): JsonResponse
    {
        return ShortResponse::json(User::all());
    }

    public function getSelf (Request $request): JsonResponse
    {
        return ShortResponse::json($request->user());
    }

    public function userById (Request $request, User $userid): JsonResponse
    {
        return ShortResponse::json($userid);
    }

    public function register (Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:50',
            'phone_number' => 'required|string|unique:users,phone_number|min:10|max:13',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)]
        ]);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $user->role_id = 1;

        return Login::in($user);
    }

    public function login (Request $request): JsonResponse
    {
        if( Auth::attempt(['email' => $request->email, 'password' => $request->password ]) )
            return Login::in( Auth::user() );

        return ShortResponse::json(['message' => 'Invalid login or password'], 401);
    }

    public function editRole (Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'role_id' => 'required|integer'
        ]);

        $user->update($data);
        return ShortResponse::json(['message' => 'User role changed']);
    }

    public function update (Request $request, User $user): JsonResponse
    {
        if( $request->user()->id != $user->id and !$request->user()->tokenCan('role-admin') )
            return ShortResponse::json(['message' => 'User not found'], 403);

        $data = $request->validate([
            'name' => 'nullable|string|min:2|max:50',
            'phone_number' => 'nullable|string|unique:users,phone_number|min:10|max:13',
            'email' => 'nullable|email|unique:users,email',
        ]);

        $user->update($data);
        return ShortResponse::json(['message' => 'User information updated']);
    }

    public function changePassword (Request $request, User $user): JsonResponse
    {
        if( $request->user()->id != $user->id )
            return ShortResponse::json([], 403);

        $data = $request->validate([
            'old_password' => ['required', Password::min(8)],
            'password' => ['required', Password::min(8)]
        ]);

        if($user->password != $data['old_password'])
            return ShortResponse::errorMessage('Old password does not match');


        unset($data['old_password']);
        $user->update($data);
        return ShortResponse::json($user);
    }

    public function delete (Request $request, User $user): JsonResponse
    {
        if( $request->user()->id != $user->id and !$request->user()->tokenCan('ability:role-admin') )
            return ShortResponse::json(['message' => 'User not found'], 403);

        $user->delete();
        return ShortResponse::json(['message' => 'User was deleted']);
    }

}
