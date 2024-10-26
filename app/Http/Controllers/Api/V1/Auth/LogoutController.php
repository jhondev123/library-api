<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (!$user = auth('sanctum')->user()) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        $token = $user->currentAccessToken();
        if ($token) {

            $token->delete();
            return response()->json(['message' => 'Token revogado com sucesso']);
        }

        return response()->json(['error' => 'Token não encontrado ou já revogado'], 404);
    }

}
