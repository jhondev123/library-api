<?php

$validData = [
    'name' => 'example',
    'email' => 'test@gmail.com',
    'password' => 'abc123@',
    'password_confirmation' => 'abc123@',
];




it('testando cadastrar um usuário com dados válidos', function () use ($validData) {

    $response = $this->postJson(route('register'), $validData);
    $response->assertStatus(201);
    $response->assertJsonStructure([
        'message',
        'data' => [
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
        ]
    ]);
    $response->assertJsonFragment([
        'name' => $validData['name'],
        'email' => $validData['email'],
    ]);
});

it('testando cadastrar um usuário com nome inválido', function () use ($validData) {
    $invalidName = "123";
    $validData = [
        'name' => $invalidName,
        'email' => $validData['email'],
        'password' => $validData['password'],
        'password_confirmation' => $validData['password_confirmation'],
    ];
    $response = $this->postJson(route('register'), $validData);
    $response->assertStatus(400);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'name'
        ]
    ]);
});

it('testando cadastrar um usuário com email inválido',function () use ($validData){

   $response = $this->postJson(route('register'),[
       'name' => $validData['name'],
       'email' => 'invalid-email',
       'password' => $validData['password'],
       'password_confirmation' => $validData['password_confirmation'],
   ]);
    $response->assertStatus(400);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'email'
        ]
    ]);
});

it('testando cadastrar um usuário com senha inválida',function () use ($validData){

    $response = $this->postJson(route('register'),[
        'name' => $validData['name'],
        'email' => $validData['email'],
        'password' => 'invalid-password',
        'password_confirmation' => $validData['password_confirmation'],
    ]);
    $response->assertStatus(400);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'password'
        ]
    ]);
});

it('testando cadastrar um usuário com a confirmação divergente da senha',function () use ($validData){

    $response = $this->postJson(route('register'),[
        'name' => $validData['name'],
        'email' => $validData['email'],
        'password' => $validData['password'],
        'password_confirmation' => 'invalid-password-confirmation',
    ]);
    $response->assertStatus(400);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'password'
        ]
    ]);
});

it('testando cadastrar um usuário com uma senha com somente numeros',function() use ($validData){

    $response = $this->postJson(route('register'),[
        'name' => $validData['name'],
        'email' => $validData['email'],
        'password' => '123456',
        'password_confirmation' => '123456',
    ]);
    $response->assertStatus(400);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'password'
        ]
    ]);
});

