<?php


use App\Models\User;

test('testando alterar senha', function () {
    $user = User::factory()->create();

    $response = $this->put(route('users.password.update',['user' => $user->id]), [
        'password' => 'a123456$',
        'password_confirmation' => 'a123456$',
    ], $this->getAuthorizationHeader());

    $response->assertStatus(200);
    $response->assertJsonStructure(['message']);
});

test('testando alterar senha com senha inválida', function () {
    $user = User::factory()->create();

    $response = $this->put(route('users.password.update',['user' => $user->id]), [
        'password' => '123456',
        'password_confirmation' => '123456',
    ], $this->getAuthorizationHeader());

    $response->assertStatus(400);
    $response->assertJsonStructure(['message','errors']);
});

test('testando alterar senha com senha não confirmada', function () {
    $user = User::factory()->create();

    $response = $this->put(route('users.password.update',['user' => $user->id]), [
        'password' => 'a123456$',
        'password_confirmation' => 'a123456',
    ], $this->getAuthorizationHeader());

    $response->assertStatus(400);
    $response->assertJsonStructure(['message','errors']);
});

test('testando alterar senha sem estar autenticado', function () {
    $user = User::factory()->create();
    $response = $this->put(route('users.password.update',['user' => $user->id]));

    $response->assertStatus(401);
});
