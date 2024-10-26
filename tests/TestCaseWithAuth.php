<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCaseWithAuth extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('Test Token')->plainTextToken;
    }

    protected function getAuthorizationHeader()
    {
        return ['Authorization' => 'Bearer ' . $this->token];
    }
}
