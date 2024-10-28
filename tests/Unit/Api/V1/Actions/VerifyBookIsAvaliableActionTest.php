<?php

use App\Actions\Book\VerifyBookIsAvaliableAction;
use App\Models\Book;
beforeEach(function () {
    // mocks
    $this->bookMockAvailiable = Mockery::mock(Book::class);
    $this->bookMockAvailiable->allows('getAttribute')
        ->with('status')
        ->andReturns('available');

    $this->bookMockUnavailable = Mockery::mock(Book::class);
    $this->bookMockUnavailable->allows('getAttribute')
        ->with('status')
        ->andReturns('unavailable');

    $this->bookMockWithoutField = Mockery::mock(Book::class);

});

test('verifica se o livro está disponível', function () {
    $action =  VerifyBookIsAvaliableAction::execute($this->bookMockAvailiable);
    expect($action)->toBeTrue();
});

test('verifica se o livro não está disponível', function () {
    $action =  VerifyBookIsAvaliableAction::execute($this->bookMockUnavailable);
    expect($action)->toBeFalse();
});

test('verifica se o livro não tem o campo status', function () {
    expect(fn() => VerifyBookIsAvaliableAction::execute($this->bookMockWithoutField))
        ->toThrow(BadMethodCallException::class);

});

