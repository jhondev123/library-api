<?php

use App\Models\User;

test('testa trazer todos os usuários', function () {
    User::factory(10)->create();
    $response = $this->get(route('users.index'), $this->getAuthorizationHeader());

    $response->assertStatus(200);
    $response->assertJsonStructure(expectedUserJsonStructure());

});


test('testando buscar usuários sem estar autenticado', function () {
    $response = $this->get(route('users.index'));

    $response->assertStatus(401);
    $response->assertJsonStructure(['message']);

});
