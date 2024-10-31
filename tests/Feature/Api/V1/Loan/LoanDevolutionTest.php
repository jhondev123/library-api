<?php

use App\Models\Loan;

test('Testando devolver um livro', function () {
    $loan = Loan::factory()->create([
        'status' => 'open',
    ]);
    $response = $this->post(route('loans.devolution', $loan->id), [
        'devolution_date' => now()->format('Y-m-d'),
        'observation' => 'Devolução do livro',
    ], $this->getAuthorizationHeader());

    $response->assertStatus(200);

    $response->assertJsonStructure(expectedOneLoanJsonStructure());
    $response->assertJsonFragment([
        'status' => 'Fechado',
        'observacao' => 'Devolução do livro',
    ]);

});

test('testando devolver um empréstimo ja devolvido', function () {
    $loan = Loan::factory()->create([
        'status' => 'closed',
    ]);
    $response = $this->post(route('loans.devolution', $loan->id), [
        'devolution_date' => now()->format('Y-m-d'),
        'observation' => 'Devolução do livro',
    ], $this->getAuthorizationHeader());

    $response->assertStatus(400);

    $response->assertJsonStructure(expectedErrorJsonStructure());

});


test('testando devolver com usuário não autenticado',function (){
    $loan = Loan::factory()->create();
    $response = $this->post(route('loans.devolution',$loan->id),[
        'return_date' => now()->addDays(7)->format('Y-m-d'),
        'observation' => 'Livro emprestado para estudo',
    ]);

    $response->assertStatus(401);
    $response->assertJsonStructure(['message']);

});
