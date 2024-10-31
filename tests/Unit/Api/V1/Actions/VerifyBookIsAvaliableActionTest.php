<?php

use App\Models\Book;

use App\Actions\Book\VerifyBookIsAvaliableAction;
beforeEach(function () {
    // Mocks de livros com diferentes estados
    $this->bookMockAvailable = Mockery::mock(Book::class)->makePartial();
    $this->bookMockAvailable->allows('getAttribute')
        ->with('status')
        ->andReturns('available');

    $this->bookMockUnavailable = Mockery::mock(Book::class)->makePartial();
    $this->bookMockUnavailable->allows('getAttribute')
        ->with('status')
        ->andReturns('unavailable');

    $this->bookMockWithoutField = Mockery::mock(Book::class)->makePartial();
});

test('verifica se o livro está disponível', function () {
    $action = new VerifyBookIsAvaliableAction();
    $result = $action->execute($this->bookMockAvailable);
    expect($result)->toBeTrue();
});

test('verifica se o livro não está disponível', function () {
    $action = new VerifyBookIsAvaliableAction();
    $result = $action->execute($this->bookMockUnavailable);
    expect($result)->toBeFalse();
});

test('verifica se o livro não tem o campo status', function () {
    $action = new VerifyBookIsAvaliableAction();
    expect(fn() => $action->execute($this->bookMockWithoutField))
        ->toThrow(BadMethodCallException::class);
});

