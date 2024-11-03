<?php

use App\Models\User;

test('testando deletar um usuário', function () {
    $user = User::factory()->create();
    $response = $this->delete(route('users.destroy', $user->id), [], $this->getAuthorizationHeader());

    $response->assertStatus(201);
});

test('testando deletar um usuário que não existe', function () {
    $response = $this->delete(route('users.destroy', 3), [], $this->getAuthorizationHeader());
    $response->assertStatus(404);
});
test('testando deletar um usuário sem estar autenticado', function () {
    $user = User::factory()->create();
    $response = $this->delete(route('users.destroy', $user->id));

    $response->assertStatus(401);
    $response->assertJsonStructure(['message']);
});
