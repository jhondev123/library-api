<?php

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;

beforeEach(function () {
    //$this->loan = Loan::factory()->create();
    $this->book = Book::factory()->create();
    $this->user = User::factory()->create();
});

test('testando criar um empréstimo e devolver em seguida', function () {
    // criando um empréstimo
    $response = $this->post(route('loans.store'), [
        'user_id' => $this->user->id,
        'book_id' => $this->book->id,
    ], $this->getAuthorizationHeader());

    $response->assertStatus(201);
    $response->assertJsonStructure(expectedOneLoanJsonStructure());
    $loan_id = $response->json('data')['id'];

    // devolvendo o livro
    $response = $this->post(route('loans.devolution', $loan_id), [], $this->getAuthorizationHeader());
    $response->assertStatus(200);
    $response->assertJsonStructure(expectedOneLoanJsonStructure());

    // testando o status do livro is avaliable
    $this->assertDatabaseHas('books', [
        'id' => $this->book->id,
        'status' => 'available',
    ]);

});
