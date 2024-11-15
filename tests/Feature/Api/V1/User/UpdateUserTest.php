<?php

use App\Models\User;

test('testando atualizar os dados do usuário', function () {
    $user = User::factory()->create();
    $name = 'teste';
    $email = 'teste@gmail.com';
    $response = $this->put(route('users.update', ['user' => $user->id]), [
        'name' => $name,
        'email' => $email
    ], $this->getAuthorizationHeader());

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'message',
        'data' => [
            'id',
            'nome',
            'email',
        ]
    ]);
    $response->assertJsonFragment([
        'nome' => $name,
        'email' => $email,
    ]);
});

test('testando atualizar somente o nome', function () {
    $user = User::factory()->create();
    $name = 'teste';
    $response = $this->put(route('users.update', ['user' => $user->id]), [
        'name' => $name,
    ], $this->getAuthorizationHeader());

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'message',
        'data' => [
            'id',
            'nome',
            'email',
        ]
    ]);
    $response->assertJsonFragment([
        'nome' => $name,
    ]);
});

test('testando atualizar somente o email', function () {
    $user = User::factory()->create();

    $email = 'teste@gmail.com';
    $response = $this->put(route('users.update', ['user' => $user->id]), [
        'email' => $email
    ], $this->getAuthorizationHeader());

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'message',
        'data' => [
            'id',
            'nome',
            'email',
        ]
    ]);
    $response->assertJsonFragment([
        'email' => $email,
    ]);

});

test('testando atualizar os dados do usuário com email já existente', function () {

    $user = User::factory()->create();
    $user2 = User::factory()->create();

    $response = $this->put(route('users.update', ['user' => $user->id]), [
        'email' => $user2->email
    ], $this->getAuthorizationHeader());

    $response->assertStatus(400);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'email'
        ]
    ]);
});

test('testando atualizar com nome inválido', function () {
    $user = User::factory()->create();

    $response = $this->put(route('users.update', ['user' => $user->id]), [
        'name' => '123',
    ], $this->getAuthorizationHeader());

    $response->assertStatus(400);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'name'
        ]
    ]);

});

test('testando atualizar com email inválido',function(){
    $user = User::factory()->create();

    $response = $this->put(route('users.update', ['user' => $user->id]), [
        'email' => 'teste',
    ], $this->getAuthorizationHeader());

    $response->assertStatus(400);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'email'
        ]
    ]);

});


test('testando atualizar os dados do usuário sem estar autenticado', function () {
    $response = $this->put(route('users.update', ['user' => 1]), [
        'name' => 'teste',
        'email' => 'teste@gmail.com'
    ]);

    $response->assertStatus(401);
});



