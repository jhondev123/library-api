<?php

it('store new book', function () {
    $response = $this->post(route('books.store'), [
        'title' => 'The Lord of the Rings',
        'author' => 'J. R. R. Tolkien',
        'description' => 'The Lord of the Rings is an epic high-fantasy novel written  J. R. R. Tolkien.',
    ]);

    $response->assertStatus(200);
});

it('store new book without description', function () {
    $response = $this->post(route('books.store'), [
        'title' => 'The Lord of the Rings',
        'author' => 'J. R. R. Tolkien',
    ]);

    $response->assertStatus(422);
});

it('store new book without title', function () {
    $response = $this->post(route('books.store'), [
        'author' => 'J. R. R. Tolkien',
        'description' => 'The Lord of the Rings is an epic high-fantasy novel written by J. R. R. Tolkien.',
    ]);

    $response->assertStatus(422);
});

it('store new book without author', function () {
    $response = $this->post(route('books.store'), [
        'title' => 'The Lord of the Rings',
        'description' => 'The Lord of the Rings is an epic high-fantasy novel written by J. R. R. Tolkien.',
    ]);

    $response->assertStatus(422);
});


