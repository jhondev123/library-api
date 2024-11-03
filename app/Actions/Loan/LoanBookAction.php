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
     * @param Book $book
     * @param User $user
     * @param StoreLoanDto $dto
     * @return Loan
     * @throws BookUnavailableException
     * @throws UserHasBorrowedBooksException
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
