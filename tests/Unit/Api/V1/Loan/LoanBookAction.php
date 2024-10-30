<?php
use App\Actions\Book\VerifyBookIsAvaliableAction;
use App\Actions\Loan\LoanBookAction;
use App\Actions\User\VerifyUserHasBorrowedBooksAction;
use App\Dtos\Loan\StoreLoanDto;
use App\Exceptions\Book\BookUnavailableException;
use App\Exceptions\Loan\UserHasBorrowedBooksException;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;

beforeEach(function () {
    $this->userMock = Mockery::mock(User::class);
    $this->bookMock = Mockery::mock(Book::class);
    $this->loanMock = Mockery::mock(Loan::class);

    $this->verifyBookAction = Mockery::mock(VerifyBookIsAvaliableAction::class);
    $this->verifyUserAction = Mockery::mock(VerifyUserHasBorrowedBooksAction::class);

    $this->action = new LoanBookAction(
        $this->verifyBookAction,
        $this->verifyUserAction,
        $this->loanMock
    );

    $this->loanData = StoreLoanDto::fromRequest([
        'user_id' => 1,
        'book_id' => 1,
        'loan_date' => now(),
        'return_date' => now()->addDays(30),
        'status' => 'open',
        'delivery_status' => 'ok',
    ]);
});

test('não deve criar empréstimo com livro indisponível', function () {
    // Arrange
    $this->verifyBookAction
        ->expects('execute')
        ->with($this->bookMock)
        ->andReturns(false);

    // Act & Assert
    expect(fn () => $this->action->execute($this->bookMock, $this->userMock, $this->loanData))
        ->toThrow(BookUnavailableException::class, 'Livro não disponível para empréstimo');
});

test('não deve criar empréstimo para usuário que já tem livros emprestados', function () {
    // Arrange
    $this->verifyBookAction
        ->expects('execute')
        ->with($this->bookMock)
        ->andReturns(true);

    $this->verifyUserAction
        ->expects('execute')
        ->with($this->userMock)
        ->andReturns(true);

    // Act & Assert
    expect(fn () => $this->action->execute($this->bookMock, $this->userMock, $this->loanData))
        ->toThrow(UserHasBorrowedBooksException::class, 'Usuário já tem livros emprestados');
});

test('deve criar um empréstimo com sucesso', function () {
    // Arrange
    $this->verifyBookAction
        ->expects('execute')
        ->with($this->bookMock)
        ->andReturns(true);

    $this->verifyUserAction
        ->expects('execute')
        ->with($this->userMock)
        ->andReturns(false);

    $this->loanMock
        ->expects('create')
        ->with($this->loanData->toArray())
        ->andReturnSelf();

    $this->bookMock->expects('update')
        ->with(['status' => 'unavailable'])
        ->andReturns(true);

    $this->userMock->expects('update')
        ->with(['has_borrowed_books' => true])
        ->andReturns(true);

    // Act
    $result = $this->action->execute($this->bookMock, $this->userMock, $this->loanData);

    // Assert
    expect($result)->toBe($this->loanMock);
});

afterEach(function () {
    Mockery::close();
});
