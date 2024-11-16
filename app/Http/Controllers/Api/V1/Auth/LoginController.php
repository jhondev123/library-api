<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use HttpResponse;

    /**
     * Lida com a requisição de login.
     *
     * Este metodo valida a requisição de login, tenta autenticar o usuário
     * e retorna uma resposta JSON com os dados do usuário e o token de acesso, se bem-sucedido.
     *
     * @param Request $request A requisição contendo as credenciais de login.
     * @return JsonResponse Uma resposta JSON com os dados do usuário e o token de acesso, ou uma mensagem de erro.
     *
     *  Parâmetros esperados no corpo da requisição:
     *  - return_date (required|email): Email de acesso do usuario.
     *  - observation (required): senha de acesso.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            Log::error('Erro ao autenticar o usuário'. $validator->errors());
            return $this->error('Erro ao autenticar o usuário', 422, $validator->errors());
        }

        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            Log::info("Tentativa de login com credenciais inválidas: {$credentials['email']}");

            return $this->error('Unauthorized', 401);

        }

        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info("Usuário logado: {$user->name} ({$user->email})");

        return $this->response('Usuário autenticado', 200, [
            'user_data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);


    }
}
