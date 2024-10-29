<?php

use App\Models\Loan;

test('testa deletar um empréstimo', function () {
    $loan = Loan::factory()->create();
    $response = $this->delete(route('loans.destroy', $loan->id),[],$this->getAuthorizationHeader());
    $response->assertStatus(204);
});

test('testa deletar um empréstimo que não existe', function () {
    $response = $this->delete(route('loans.destroy', 3),[],$this->getAuthorizationHeader());
    $response->assertStatus(404);
});

test('testa deletar um livro com um usuário não autenticado', function () {
    $loan = Loan::factory()->create();
    $response = $this->delete(route('loans.destroy', $loan->id));

    $response->assertStatus(401);
});
