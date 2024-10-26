<?php

use App\Models\Book;

$title = 'The Lord of the Rings';
$author = 'J. R. R. Tolkien';
$description = 'The Lord of the Rings is an epic high-fantasy novel written by J. R. R. Tolkien.';

it('testa atualizar todos os dados do livro', function () use ($title, $author, $description) {
    $book = Book::factory()->create();
    $response = $this->put(route('books.update', $book->id), [
        'title' => $title,
        'author' => $author,
        'description' => $description,
    ],$this->getAuthorizationHeader());

    $response->assertStatus(200);
    $response->assertJsonStructure(expectedOneBookJsonStructure());
    $this->assertEquals($title, $book->fresh()->title);
    $this->assertEquals($author, $book->fresh()->author);
    $this->assertEquals($description, $book->fresh()->description);
});

it('testa atualizar o titulo do livro', function () use ($title) {
    $book = Book::factory()->create();
    $response = $this->put(route('books.update', $book->id), [
        'title' => $title,
    ],$this->getAuthorizationHeader());
    $response->assertStatus(200);
    $response->assertJsonStructure(expectedOneBookJsonStructure());
    $this->assertEquals($title, $book->fresh()->title);
});

it('testa atualizar o autor do livro', function () use ($author) {
    $book = Book::factory()->create();
    $response = $this->put(route('books.update', $book->id), [
        'author' => $author,
    ],$this->getAuthorizationHeader());
    $response->assertStatus(200);
    $response->assertJsonStructure(expectedOneBookJsonStructure());
    $this->assertEquals($author, $book->fresh()->author);
});

it('testa atualizar a descrição do livro',function () use ($description) {
    $book = Book::factory()->create();
    $response = $this->put(route('books.update', $book->id), [
        'description' => $description,
    ],$this->getAuthorizationHeader());
    $response->assertStatus(200);
    $response->assertJsonStructure(expectedOneBookJsonStructure());
    $this->assertEquals($description, $book->fresh()->description);
});

it('testa atualizar com um autor inválido',function (){
   $book = Book::factory()->create();
    $response = $this->put(route('books.update', $book->id), [
         'author' => 123,
    ],$this->getAuthorizationHeader());
    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
});

it('testa atualizar com um titulo inválido',function (){
    $book = Book::factory()->create();
    $response = $this->put(route('books.update', $book->id), [
        'title' => 123,
    ],$this->getAuthorizationHeader());
    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
});

it('testa atualizar com usuário inválido',function() use ($title,$author,$description){
    $book = Book::factory()->create();
    $response = $this->put(route('books.update', $book->id), [
        'title' => $title,
        'author' => $author,
        'description' => $description,
    ]);
    $response->assertStatus(401);

});
