<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $dados['email'])->first();
        if (!$user || !Hash::check($dados['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Dados de login inválidos']
            ]);
        }

        $token = $user->createToken($request->email)->plainTextToken;
        return response()->json([
            'token' => $token,
        ]);
    }

    public function validaToken()
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'];

        if (empty($token)) {
            return response()->json(['valid' => false], 401);
        }

        if (Auth::guard('api')->check()) {
            return response()->json(['valid' => true], 200);
        }

        return response()->json(['valid' => false], 401);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
    }
}
