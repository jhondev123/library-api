<?php

test('index, trás todos os empréstimos', function () {
    \App\Models\Loan::factory(10)->create();
    $response = $this->get(route('loans.index'), $this->getAuthorizationHeader());


    $response->assertStatus(200);

    $response->assertJsonCount(10, 'data');
    $response->assertJsonStructure(expectedLoanJsonStructure());
});

it('testa pesquisa com usuário não autenticado', function () {

    $response = $this->get(route('loans.index'));

    $response->assertStatus(401);
});

