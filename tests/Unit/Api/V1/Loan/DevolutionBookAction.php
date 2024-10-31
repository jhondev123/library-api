<?php

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use App\Actions\Loan\DevolutionBookAction;
use App\Dtos\Loan\DevolutionLoanDto;
use Illuminate\Support\Carbon;

it('atualiza o status do livro, do usuário, e os campos de observação e data de devolução ao devolver um empréstimo', function () {
    // Cria mocks para as dependências
    $book = Mockery::mock(Book::class);
    $user = Mockery::mock(User::class);
    $loan = Mockery::mock(Loan::class);

    // Configura os relacionamentos simulados entre as models
    $loan->allows('getAttribute')->with('book')->andReturns($book);
    $loan->allows('getAttribute')->with('user')->andReturns($user);

    // Configura as expectativas para as atualizações
    $book->expects('update')->with(['status' => 'available']);
    $user->expects('update')->with(['has_borrowed_books' => false]);
    $loan->expects('update')->with([
        'observation' => 'devolvido',
        'devolution_date' => now()->toDateString(),
    ]);

    $dto = new DevolutionLoanDto(
        observation: 'devolvido',
        devolution_date: now()->toDateString()
    );

    $action = new DevolutionBookAction();

    $result = $action->execute($loan, $dto);

    expect($result)->toBeInstanceOf(Loan::class)
        ->toBe($loan);

});

