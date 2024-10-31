<?php

use App\Models\User;
use App\Actions\User\VerifyUserHasBorrowedBooksAction;

beforeEach(function () {
    // Mock do usuário que possui empréstimos
    $this->userMockTrue = Mockery::mock(User::class)->makePartial();
    $this->userMockTrue->allows('getAttribute')
        ->with('has_borrowed_books')
        ->andReturns(true);

    // Mock do usuário que não possui empréstimos
    $this->userMockFalse = Mockery::mock(User::class)->makePartial();
    $this->userMockFalse->allows('getAttribute')
        ->with('has_borrowed_books')
        ->andReturns(false);

    // Mock de usuário sem o campo `has_borrowed_books`
    $this->userMockWithoutField = Mockery::mock(User::class)->makePartial();
});

test('verifica se o usuário possui empréstimos', function () {
    $action = new VerifyUserHasBorrowedBooksAction();
    $result = $action->execute($this->userMockTrue);
    expect($result)->toBeTrue();
});

test('verifica se o usuário não possui empréstimos', function () {
    $action = new VerifyUserHasBorrowedBooksAction();
    $result = $action->execute($this->userMockFalse);
    expect($result)->toBeFalse();
});

test('verifica se o usuário não possui o campo has_borrowed_books', function () {
    $action = new VerifyUserHasBorrowedBooksAction();
    expect(fn() => $action->execute($this->userMockWithoutField))
        ->toThrow(BadMethodCallException::class);
});
