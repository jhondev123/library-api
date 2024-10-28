<?php

use App\Models\User;
use App\Actions\User\VerifyUserHasBorrowedBooksAction;

beforeEach(function () {
    // mocks
    $this->userMockTrue = Mockery::mock(User::class);
    $this->userMockTrue->allows('getAttribute')
        ->with('has_borrowed_books')
        ->andReturns(true);

    $this->userMockFalse = Mockery::mock(User::class);
    $this->userMockFalse->allows('getAttribute')
        ->with('has_borrowed_books')
        ->andReturns(false);

    $this->userMockWithoutField = Mockery::mock(User::class);

});

test('verifica se o usuário possui empréstimos', function () {

    $action  = VerifyUserHasBorrowedBooksAction::execute($this->userMockTrue);
    expect($action)->toBeTrue();

});
test('verifica se o usuário não possui empréstimos', function () {

        $action  = VerifyUserHasBorrowedBooksAction::execute($this->userMockFalse);
        expect($action)->toBeFalse();

});

test('verifica se o usuário não possui o campo has_borrowed_books', function () {
    expect(fn() => VerifyUserHasBorrowedBooksAction::execute($this->userMockWithoutField))
        ->toThrow(BadMethodCallException::class);
});
