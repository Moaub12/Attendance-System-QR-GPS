<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Auth extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Wrong credentials',
            ], 500);
        } else {
            $user = auth()->user();
           
            $authToken = $user->createToken('auth-token')->plainTextToken;
           

            return response()->json([
                'response' => [
                    'session_id' => $authToken,
                    'uid' => $user->id,
                ],
                'message' => 'login complete'
            ], Response::HTTP_OK);
        }
    }

}
