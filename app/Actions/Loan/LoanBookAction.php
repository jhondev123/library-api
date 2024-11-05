<?php

namespace App\Actions\Loan;

use App\Actions\Book\VerifyBookIsAvaliableAction;
use App\Actions\User\VerifyUserHasBorrowedBooksAction;
use App\Dtos\Loan\StoreLoanDto;
use App\Enums\BookStatus;
use App\Exceptions\Book\BookUnavailableException;
use App\Exceptions\Loan\UserHasBorrowedBooksException;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Traits\HttpResponse;

class LoanBookAction
{
    use HttpResponse;

    public function __construct(
        private VerifyBookIsAvaliableAction      $verifyBookIsAvaliableAction,
        private VerifyUserHasBorrowedBooksAction $verifyUserHasBorrowedBooksAction,
        private Loan                             $loan
    )
    {
    }

    /**
     * Executa a ação de empréstimo de um livro.
     *
     * @param Book $book O livro a ser emprestado.
     * @param User $user O usuário que está pegando o livro emprestado.
     * @param StoreLoanDto $dto Os dados do empréstimo.
     * @return Loan Retorna o empréstimo criado.
     * @throws BookUnavailableException Se o livro não estiver disponível para empréstimo.
     * @throws UserHasBorrowedBooksException Se o usuário já tiver livros emprestados.
     */
    public function execute(Book $book, User $user, StoreLoanDto $dto): Loan
    {
        // verifica se o livro já está emprestado
        if (!$this->verifyBookIsAvaliableAction->execute($book)) {
            throw new BookUnavailableException('Livro não disponível para empréstimo');
        }

        // verifica se o usuário já tem livros emprestados
        if ($this->verifyUserHasBorrowedBooksAction->execute($user)) {
            throw new UserHasBorrowedBooksException('Usuário já tem livros emprestados');
        }

        $loan = $this->loan->create($dto->toArray());

        $book->update(['status' => BookStatus::Unavailable->value]);
        $user->update(['has_borrowed_books' => true]);

        return $loan;
    }
}
