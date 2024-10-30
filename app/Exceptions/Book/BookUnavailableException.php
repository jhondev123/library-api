<?php

namespace App\Exceptions\Book;

class BookUnavailableException extends \Exception
{
    public function __construct($message = 'Livro não disponível para empréstimo', $code = 422, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
