<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\User\UserResource;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->response("Usuários", 200, UserResource::collection(User::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->response("Usuário", 200, new UserResource($user));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->response("Usuário deletado com sucesso", 201);


    }
}
