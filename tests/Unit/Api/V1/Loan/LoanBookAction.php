<?php

use App\Actions\Book\VerifyBookIsAvaliableAction;
use App\Actions\Loan\LoanBookAction;
use App\Actions\User\VerifyUserHasBorrowedBooksAction;
use App\Http\Resources\Api\V1\Loan\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

beforeEach(function () {
    $this->userMock = Mockery::mock(User::class);
    $this->bookMock = Mockery::mock(Book::class);

    $this->verifyBookAction = Mockery::mock(VerifyBookIsAvaliableAction::class);
    $this->verifyUserAction = Mockery::mock(VerifyUserHasBorrowedBooksAction::class);



    $this->mock(ResponseFactory::class, function ($mock) {
        $mock->shouldReceive('json')
            ->once()
            ->andReturn(new JsonResponse(['key' => 'value'], 200));
    });

    // Instancia a ação de empréstimo de livro
    $this->action = new LoanBookAction(
        $this->verifyBookAction,
        $this->verifyUserAction
    );

    $this->data = [
        'user_id' => 1,
        'book_id' => 1,
        'loan_date' => now(),
        'return_date' => now()->addDays(30),
        'status' => 'open',
        'delivery_status' => 'ok',
    ];
});

test('Teste que não pode criar um empréstimo com livro indisponível', function () {
    // Arrange
    $this->verifyBookAction
        ->expects('execute')
        ->with($this->bookMock)
        ->andReturns(false);

    // Act
    $response = $this->action->execute($this->bookMock, $this->userMock, $this->data);

    // Assert
    expect($response->getStatusCode())->toBe(422)
        ->and(json_decode($response->getContent())->message)
        ->toBe('Livro não disponível para empréstimo');
});

test('testa que não pode criar um empréstimo com um usuário que já tem livros emprestados', function () {
    // Arrange
    $this->verifyBookAction
        ->expects('execute')
        ->with($this->bookMock)
        ->andReturns(true);

    $this->verifyUserAction
        ->expects('execute')
        ->with($this->userMock)
        ->andReturns(true);

    // Act
    $response = $this->action->execute($this->bookMock, $this->userMock, $this->data);

    // Assert
    expect($response->getStatusCode())->toBe(422)
        ->and(json_decode($response->getContent())->message)
        ->toBe('Usuário já tem livros emprestados');
});

test('tenta criar um empréstimo com sucesso', function () {
    // Arrange
    $this->verifyBookAction
        ->expects('execute')
        ->with($this->bookMock)
        ->andReturns(true);

    $this->verifyUserAction
        ->expects('execute')
        ->with($this->userMock)
        ->andReturns(false);

    $loanMock = Mockery::mock(Loan::class);
    Loan::shouldReceive('create')
        ->once()
        ->with($this->data)
        ->andReturn($loanMock);

    $this->bookMock->expects('update')
        ->with(['status' => 'unavailable'])
        ->andReturns(true);

    $this->userMock->expects('update')
        ->with(['has_borrowed_books' => true])
        ->andReturns(true);

    mock(LoanResource::class)
        ->expects('__construct')
        ->with($loanMock)
        ->andReturnSelf();

    // Act
    $response = $this->action->execute($this->bookMock, $this->userMock, $this->data);

    // Assert
    expect($response->getStatusCode())->toBe(201)
        ->and(json_decode($response->getContent())->message)
        ->toBe('Empréstimo criado com sucesso');
});

afterEach(function () {
    Mockery::close();
});
