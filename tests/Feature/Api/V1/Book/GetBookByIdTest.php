<?php

use App\Models\Book;


it('Deveria trazer um livro', function () {
    $book = Book::factory()->create();
    $response = $this->get(route('books.show', $book->id), $this->getAuthorizationHeader()
    );
    $response->assertStatus(200);

    $response->assertJsonStructure(expectedOneBookJsonStructure());

});
it('Tenta procurar um livro que não existe', function () {
    $response = $this->get(route('books.show', 1), $this->getAuthorizationHeader());
    $response->assertStatus(404);
});

it('testa pesquisa com usuário não autenticado', function () {
    $book = Book::factory()->create();
    $response = $this->get(route('books.show', $book->id));

    $response->assertStatus(401);
});
