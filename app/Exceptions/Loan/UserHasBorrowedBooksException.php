<?php

namespace App\Exceptions\Loan;

class UserHasBorrowedBooksException extends \Exception
{
    public function __construct($message = 'Usuário já tem livros emprestados', $code = 422, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
