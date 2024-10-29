<?php

namespace App\Actions\Book;

use App\Enums\BookStatus;
use App\Models\Book;

class VerifyBookIsAvaliableAction
{

    public function execute(Book $book)
    {
        return $book->status === BookStatus::Available->value;
    }


}
