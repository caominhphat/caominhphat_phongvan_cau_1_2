<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    public function login (Request $request) {
        try {
            $request = $request->only(
                [
                    'email',
                    'password',
                ]
            );

            $validate = Validator::make($request,
                [
                    'email' => 'required|email:rfc,dns',
                    'password' => 'required',
                ]);

            if ($validate->fails()) {
                return response($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if (\Illuminate\Support\Facades\Auth::attempt($request)) {
                $loginUser = \Illuminate\Support\Facades\Auth::user();
                return response('Bearer'.' '.$loginUser->createToken('authToken')->plainTextToken, Response::HTTP_CREATED);
            }

            return response('check email, password', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(\Exception $exception) {
            return response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function logout () {
        try {
            $auth = Auth::user();
            if ($auth) {
                $auth->tokens()->where('id', auth()->id())->delete();
            }
            return response('Logout success', Response::HTTP_OK);
        } catch(\Exception $exception) {
            return response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
