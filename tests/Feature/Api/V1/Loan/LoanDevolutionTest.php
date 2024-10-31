<?php

use App\Models\Loan;

test('Testando devolver um livro', function () {
    // Cria um empréstimo usando a factory
    $loan = Loan::factory()->create();

    // Faz uma requisição POST para a rota de devolução
    $response = $this->post(route('loans.devolution', $loan->id), [
        'devolution_date' => now()->format('Y-m-d'),
        'observation' => 'Devolução do livro',
    ], $this->getAuthorizationHeader());

    // Verifica se a resposta foi bem-sucedida
    $response->assertStatus(200);

    // Verifica a estrutura do JSON de resposta
    $response->assertJsonStructure(expectedOneLoanJsonStructure());

    // Verifica se o JSON contém os dados esperados
    $response->assertJsonFragment([
        'data' => [
            'data_devolucao' => now()->format('d/m/Y'), // Ajuste para o mesmo formato utilizado na requisição
            'observacao' => 'Devolução do livro', // Use a string diretamente
        ],
    ]);
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
