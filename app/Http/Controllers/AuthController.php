<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // autenmticação (email e senha)
        $credenciais = $request->all(['email', 'password']);
        $token = auth('api')->attempt($credenciais);
        
        if ($token === false) {
            return response()->json(['success' => 'false'], 403);
        }

        // retornar um JWT - Json Web Token
        return response()->json([
            'success' => 'true',
            'token' => $token
        ], 200);
    }

    public function logout()
    {
        return 'Logout';
    }

    public function refresh()
    {
        // renova a autorização de acesso se o cliente enviou um jwt valido
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
