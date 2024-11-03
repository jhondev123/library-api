<?php

test('show, busca um empréstimo pelo ID', function () {
    $loan = \App\Models\Loan::factory()->create();
    $response = $this->get(route('loans.show', $loan->id), $this->getAuthorizationHeader());
    $response->assertStatus(200);
    $response->assertJsonStructure(expectedOneLoanJsonStructure());
});

it('show, busca um empréstimo pelo ID que não existe', function () {
    $response = $this->get(route('loans.show', 1), $this->getAuthorizationHeader());
    $response->assertStatus(404);
});

it('show, busca um empréstimo pelo ID sem estar autenticado', function () {
    $loan = \App\Models\Loan::factory()->create();
    $response = $this->get(route('loans.show', $loan->id));
    $response->assertStatus(401);
});
