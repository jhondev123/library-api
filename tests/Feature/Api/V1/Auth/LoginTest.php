<?php

use App\Models\User;

it('should auth user', function () {
    $user = User::factory()->create();
    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'user_data' => [
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
        ],
        'access_token',
        'token_type',
    ]);

});

it('should not auth user', function () {

    User::create([
        'name' => 'Jhonattan2',
        'email' => 'jhonattan2@gmail.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post(route('login'), [
        'email' => 'jhonattan2@gmail.com',
        'password' => 'passwrd',
    ]);

    $response->assertStatus(401);
    $response->assertJson(['message' => 'Unauthorized']);
});

describe('validations', function () {
    it('should return validation error', function () {
        $response = $this->post(route('login'), []);

        $response->assertStatus(422);
        dump($response->getContent());

        $response->assertJsonValidationErrors(
            [
                'email' => trans('validation.required',['attribute' => 'email']),
                'password' => trans('validation.required',['attribute' => 'password']),
            ]);
    });
});
