<?php

use App\Models\Book;

test('testa deletar um livro', function () {
    $book = Book::factory()->create();
    $response = $this->delete(route('books.destroy', $book->id),[],$this->getAuthorizationHeader());
    $response->assertStatus(204);
});

test('testa deletar um livro que não existe', function () {
    $book = Book::factory()->create();

    $response = $this->delete(route('books.destroy', 3),[],$this->getAuthorizationHeader());
    $response->assertStatus(404);
});

test('testa deletar um livro com um usuário não autenticado', function () {
    $book = Book::factory()->create();
    $response = $this->delete(route('books.destroy', $book->id));

    $response->assertStatus(401);
});
