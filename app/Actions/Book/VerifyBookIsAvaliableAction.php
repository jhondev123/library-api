<?php

namespace App\Actions\Book;

use App\Enums\BookStatus;
use App\Models\Book;
use BadMethodCallException;

class VerifyBookIsAvaliableAction
{

/**
 * Verifica se um livro está disponível.
 *
 * @param Book $book O livro a ser verificado.
 * @return bool Retorna true se o livro estiver disponível e false caso contrário.
 * @throws BadMethodCallException Se o livro não tiver o campo status.
 */
public function execute(Book $book): bool
{
    if (!$book->status) {
        throw new BadMethodCallException('O livro não tem o campo status');
    }
    return $book->status === BookStatus::Available->value;
}


}
