<?php

namespace App\Actions\User;

use App\Models\User;

class VerifyUserHasBorrowedBooksAction
{
    public static function execute(User $user)
    {
        return $user->has_borrowed_books;
    }
}
