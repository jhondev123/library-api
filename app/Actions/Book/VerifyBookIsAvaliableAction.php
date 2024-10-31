<?php

namespace App\Actions\Book;

use App\Enums\BookStatus;
use App\Models\Book;

class VerifyBookIsAvaliableAction
{

    public function execute(Book $book)
    {
        if (! $book->status) {
            throw new \BadMethodCallException('O livro não tem o campo status');
        }
        return $book->status === BookStatus::Available->value;
    }


}
