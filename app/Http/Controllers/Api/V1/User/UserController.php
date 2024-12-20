<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\User\UserResource;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use HttpResponse;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->response("Usuários", 200, UserResource::collection(User::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function store(Request $request,User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new \App\Rules\UsernameValidation()
            ],
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:6',
                'confirmed',
                new \App\Rules\PasswordComplexity()
            ],
        ]);

        if ($validator->fails()) {
            Log::error('Erro ao cadastrar o usuário '. $validator->errors());
            return $this->error("Erro ao cadastrar o usuário", 400, $validator->errors());
        }

        $userCreated = $user->create($validator->validated());

        Log::info("Usuário criado com sucesso. 'user_id' => $userCreated->id");

        return $this->response("Usuário criado com sucesso", 201,$userCreated);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return $this->response("Usuário", 200, new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'nullable',
                'string',
                'min:2',
                'max:255',
                new \App\Rules\UsernameValidation()
            ],
            'email' => 'nullable|email|unique:users',
        ]);

        if($validator->fails()){
            Log::error('Erro ao atualizar o usuário'. $validator->errors());
            return $this->error("Erro ao atualizar o usuário", 400, $validator->errors());
        }

        $user->update($validator->validated());
        Log::info("Usuário atualizado com sucesso. 'user_id' => $user->id");
        return $this->response("Usuário atualizado com sucesso", 200, new UserResource($user));

    }

    public function updatePassword(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'min:6',
                'confirmed',
                new \App\Rules\PasswordComplexity()
            ],
        ]);

        if($validator->fails()){
            Log::error('Erro ao atualizar a senha do usuário' . $validator->errors());

            return $this->error("Erro ao atualizar a senha do usuário", 400, $validator->errors());
        }

        $user->update($validator->validated());

        Log::info("Senha atualizada com sucesso. 'user_id' => $user->id");

        return $this->response("Senha atualizada com sucesso", 200, new UserResource($user));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        Log::info("Usuário deletado com sucesso. 'user_id' => $user->id");
        return $this->response("Usuário deletado com sucesso", 201);
    }
}
