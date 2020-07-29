<?php

namespace App\Http\Controllers\Auth;

use App\Administrator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class AdminAuthController extends Controller
{

    public function authenticate(Request $request)
    {
        $credential = $request->only('email', 'password');

        if (!$token = auth('api-admin')->attempt($credential)) {
            return response()->json([
                'error' => 'Unautorized'
            ], 400);
        }
        
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60
        ]);
    }
}
