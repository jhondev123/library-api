<?php

use App\Models\Loan;

test('update, testa atualizar um empréstimo', function () {
    $loan = Loan::factory()->create();
    $response = $this->put(route('loans.update',$loan->id),[
        'return_date' => now()->addDays(7)->format('Y-m-d'),
        'observation' => 'Livro emprestado para estudo',
    ],$this->getAuthorizationHeader());


    $response->assertStatus(200);
    $response->assertJsonStructure(expectedOneLoanJsonStructure());

});

test('testando atualizar com uma data menor que a data atual',function (){
    $loan = Loan::factory()->create();
    $response = $this->put(route('loans.update',$loan->id),[
        'return_date' => now()->subDays(7)->format('Y-m-d'),
    ],$this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['return_date']);

});

test('testando atualizar com uma data inválida',function (){
    $loan = Loan::factory()->create();
    $response = $this->put(route('loans.update',$loan->id),[
        'return_date' => 'abc',
    ],$this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['return_date']);

});
test('testando atualizar sem passar nada',function (){

    $loan = Loan::factory()->create();
    $response = $this->put(route('loans.update',$loan->id),[],$this->getAuthorizationHeader());

    $response->assertStatus(204);
    $response->assertNoContent();
});

test('testando atualizar com usuário não autenticado',function (){
    $loan = Loan::factory()->create();
    $response = $this->put(route('loans.update',$loan->id),[
        'return_date' => now()->addDays(7)->format('Y-m-d'),
        'observation' => 'Livro emprestado para estudo',
    ]);

    $response->assertStatus(401);
    $response->assertJsonStructure(['message']);

});

