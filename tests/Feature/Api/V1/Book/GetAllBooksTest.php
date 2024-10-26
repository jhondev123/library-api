<?php

use App\Models\Book;

it('deveria trazer todos os livros', function () {
    $books = Book::factory()->count(10)->create();

    $response = $this->get(route('books.index'), $this->getAuthorizationHeader());

    $response->assertStatus(200);
    $response->assertJsonCount(10, 'data');
    $response->assertJsonStructure(expectedBookJsonStructure());
    $response->assertJsonFragment(['title' => $books->first()->title]);
});

it('testa pesquisa com usuário não autenticado', function () {

    $response = $this->get(route('books.index'));

    $response->assertStatus(401);
});
