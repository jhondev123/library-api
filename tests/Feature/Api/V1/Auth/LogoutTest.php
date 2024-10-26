<?php

use App\Models\User;

it('user authenticated should can logout ', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
     $response = $this->post(route('logout'), [], [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(200);
});

it('user not authenticated should not can logout ', function () {
    $response = $this->post(route('logout'));

    $response->assertStatus(401);
});
