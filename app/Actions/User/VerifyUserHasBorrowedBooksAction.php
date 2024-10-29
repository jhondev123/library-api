<?php

namespace App\Actions\User;

use App\Models\User;

class VerifyUserHasBorrowedBooksAction
{
    public function execute(User $user)
    {
        return $user->has_borrowed_books;
    }
}
