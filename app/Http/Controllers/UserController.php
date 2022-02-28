<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function users (): JsonResponse
    {
        return ShortResponse::json(true, 'Users retrieved...', User::all());
    }

    public function userById (User $userid): JsonResponse
    {
        return ShortResponse::json(true, 'User by id retrieved', $userid);
    }

    public function userByLogin (User $user): JsonResponse
    {
        return ShortResponse::json(true, 'User by login retrieved', $user);
    }

    public function create (Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:50',
            'surname' => 'required|string|min:2|max:50',
            'login' => 'required|string|unique:users,login|min:4|max:32',
            'phone_number' => 'required|string|unique:users,phone_number|min:10|max:13',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)]
        ]);

        return ShortResponse::json(true, 'User created', User::create($data), 201);
    }

    public function editRole (Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'role_id' => 'required|integer'
        ]);

        $user->update($data);
        return ShortResponse::json(true, 'User role updated', $user);
    }

    public function update (Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|min:2|max:50',
            'surname' => 'nullable|string|min:2|max:50',
            'login' => 'nullable|string|unique:users,login|min:4|max:32',
            'phone_number' => 'nullable|string|unique:users,phone_number|min:10|max:13',
            'email' => 'nullable|email|unique:users,email',
        ]);

        $user->update($data);
        return ShortResponse::json(true, 'User updated', $user);

    }

    public function changePassword (Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'old_password' => ['required', Password::min(8)],
            'password' => ['required', Password::min(8)]
        ]);

        if($user->password != $data['old_password'])
            return ShortResponse::errorMessage('Old password does not match');


        unset($data['old_password']);
        $user->update($data);
        return ShortResponse::json(true, 'User password updated', $user);
    }

    public function delete (int $id): JsonResponse
    {
        return ShortResponse::delete(new User(), $id);
    }

}
