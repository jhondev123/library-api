<?php

namespace App\Actions\User;

use App\Models\User;
use BadMethodCallException;

/**
 * Class VerifyUserHasBorrowedBooksAction
 *
 * Essa classe verifica se o usuário tem livros emprestados .
 *
 * @package App\Actions\User
 */
class VerifyUserHasBorrowedBooksAction
{
    /**
     * verifica se o usuário tem livros emprestados.
     *
     * @param User $user O usuário a ser verificado.
     * @return bool Retorna verdadeiro se o usuário tiver livros emprestados, falso caso contrário.
     * @throws BadMethodCallException Se o usuário não tiver o campo `has_borrowed_books`.
     */
    public function execute(User $user): bool
    {
        if (!isset($user->has_borrowed_books)) {
            throw new \BadMethodCallException('O usuário não possui o campo `has_borrowed_books`');
        }
        return $user->has_borrowed_books;
    }
}
