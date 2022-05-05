<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Custom\ShortResponse;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPassword extends Controller
{
    private User $user;

    public function resetLink(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:App\Models\User,email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT ? ShortResponse::json(['message' => 'Check your email']) : ShortResponse::errorMessage('Error');
    }

    public function resetPassword(Request $request) : JsonResponse
    {
        $request->validate([
            'token' => 'required',
//            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $status = Password::reset(
            $request->only('password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                $this->user = $user;

                event(new PasswordReset($user));
            }
        );

        $token = $this->user->createToken('old', [ $this->user->role->permission ])->plainTextToken;
        return $status === Password::PASSWORD_RESET ? ShortResponse::json(['message' => 'Changed', 'token' => $token]) : ShortResponse::errorMessage('Error');
    }
}
