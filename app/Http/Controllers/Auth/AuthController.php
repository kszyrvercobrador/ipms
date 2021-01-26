<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['user', 'logout']);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        return response()->json([
            'access_token' => $user->createToken($request->userAgent())->plainTextToken,
            'user' => UserResource::make($user)->resolve(),
        ]);
    }

    public function user()
    {
        return UserResource::make(Auth::user());
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response('', 204);
    }
}
