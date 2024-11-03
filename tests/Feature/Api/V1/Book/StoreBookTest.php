<?php
$title = 'The Lord of the Rings';
$author = 'J. R. R. Tolkien';
$description = 'The Lord of the Rings is an epic high-fantasy novel written by J. R. R. Tolkien.';

it('Cadastra um livro', function () use ($title, $author, $description) {
    $response = $this->post(route('books.store'), [
        'title' => $title,
        'author' => $author,
        'description' => $description,
    ], $this->getAuthorizationHeader());

    $response->assertStatus(200);
    $response->assertJsonStructure(expectedOneBookJsonStructure());
});

it('Tenta cadastrar um livro sem descrição', function () use ($title, $author) {
    $response = $this->post(route('books.store'), [
        'title' => $title,
        'author' => $author,
    ], $this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
    $response->assertJsonValidationErrors(
        [
            'description' => trans('validation.required', ['attribute' => 'description']),
        ]
    );
});

it('Tenta cadastrar um livro sem titulo', function () use ($author, $description) {

    $response = $this->post(route('books.store'), [
        'author' => $author,
        'description' => $description,
    ], $this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
    $response->assertJsonValidationErrors(
        [
            'title' => trans('validation.required', ['attribute' => 'title'])
        ]
    );

});

it('Tenta cadastrar um livro sem autor', function () use ($title, $description) {
    $response = $this->post(route('books.store'), [
        'title' => $title,
        'description' => $description,
    ], $this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
    $response->assertJsonValidationErrors(
        [
            'author' => trans('validation.required', ['attribute' => 'author'])
        ]
    );
});

it('testa cadastrar um livro com author inválido', function () use ($title, $description, $author) {
    $response = $this->post(route('books.store'), [
        'title' => $title,
        'author' => 123,
        'description' => $description,
    ], $this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
    $response->assertJsonValidationErrors(
        [
            'author' => trans('validation.string', ['attribute' => 'author'])
        ]
    );

});

it('testa cadastrar um livro com title inválido', function () use ($author, $description) {

    $response = $this->post(route('books.store'), [
        'title' => 123,
        'author' => $author,
        'description' => $description,
    ], $this->getAuthorizationHeader());

    $response->assertStatus(422);
    $response->assertJsonStructure(expectedErrorJsonStructure());
    $response->assertJsonValidationErrors(
        [
            'title' => trans('validation.string', ['attribute' => 'title'])
        ]
    );
});

it('testa cadastrar com um usuário não autenticado', function () use ($title, $author, $description) {
    $response = $this->post(route('books.store'), [
        'title' => $title,
        'author' => $author,
        'description' => $description,
    ]);

    $response->assertStatus(401);

});
