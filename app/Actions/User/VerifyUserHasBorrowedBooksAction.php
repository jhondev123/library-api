<?php

namespace App\Actions\User;

use App\Models\User;

class VerifyUserHasBorrowedBooksAction
{
    public function execute(User $user)
    {
        if (!isset($user->has_borrowed_books)) {
            throw new \BadMethodCallException('O usuário não possui o campo `has_borrowed_books`');
        }
        return $user->has_borrowed_books;
    }
}
