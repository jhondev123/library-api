<?php

use App\Models\Book;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['has_borrowed_books' => false]);
    $this->book = Book::factory()->create(['status' => 'available']);

    $this->user_with_borrowed_books = User::factory()->create(['has_borrowed_books' => true]);
    $this->book_with_status_unavailable = Book::factory()->create(['status' => 'unavailable']);

    config(['app.standart_return_time' => 30]);

});

test('store, testando cadastrar um empréstimo', function () {

    $response = $this->post(route('loans.store'),[
        'book_id' => $this->book->id,
        'user_id' => $this->user->id,
    ],$this->getAuthorizationHeader());


    $response->assertStatus(201);
    $response->assertJsonStructure(expectedOneLoanJsonStructure());
});

test('testa cadastrar sem usuário e livro', function () {

    $response = $this->post(route('loans.store'),[],$this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
});
test('testa cadastar um empréstimo com livro indisponível', function () {

    $response = $this->post(route('loans.store'),[
        'book_id' => $this->book_with_status_unavailable->id,
        'user_id' => $this->user->id,
    ],$this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
});

test('testa cadastrar um empréstimo com usuário que já tem livros emprestados', function () {

    $response = $this->post(route('loans.store'),[
        'book_id' => $this->book->id,
        'user_id' => $this->user_with_borrowed_books->id,
    ],$this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
});

test('testando cadastrar um empréstimo com usuário não autenticado',function (){
    $response = $this->post(route('loans.store'),[
        'book_id' => $this->book->id,
        'user_id' => $this->user->id,
    ]);

    $response->assertStatus(401);
    $response->assertJsonStructure(['message']);
});
