<?php

use App\Models\User;

test('testando buscar um usuário pelo ID', function () {
    $user = User::factory()->create();
    $response = $this->get(route('users.show', $user->id), $this->getAuthorizationHeader());

    $response->assertStatus(200);
    $response->assertJsonStructure(expectedOneUserJsonStructure());
});

test('testando buscar um usuário que não existe', function () {
    $response = $this->get(route('users.show', 50), $this->getAuthorizationHeader());

    $response->assertStatus(404);

});

test('testando buscar um usuário pelo ID sem estar autenticado', function () {
    $user = User::factory()->create();
    $response = $this->get(route('users.show', $user->id));

    $response->assertStatus(401);
    $response->assertJsonStructure(['message']);
});
