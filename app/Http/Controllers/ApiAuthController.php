<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\ApiRegisterUserRequest;
use App\Http\Responses\ApiFailureResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\UserRegisteredResponse;
use App\Models\User;
use Auth;

class ApiAuthController extends Controller
{

    public function register(ApiRegisterUserRequest $request)
    {
        $data = $request->only(['name', 'password', 'email']);
        $data['password'] = \Hash::make($data['password']);

        $user = User::create($data);

        $token = Auth::login($user);

        return new UserRegisteredResponse($user, $token);
    }

    public function login(ApiLoginRequest $request)
    {
        $token = Auth::attempt($request->only(['email', 'password']));
        if(!$token) {
            return new ApiFailureResponse('Email or password incorrect');
        }

        $user = Auth::user();
        return new ApiSuccessResponse('Login successful', [
            'user' => $user,
            'token' => $token,
            'type' => 'bearer',
        ]);
    }

}
