<?php

namespace App\Rules\Api\V1\Book;

use App\Enums\BookStatus;
use App\Models\Book;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BookIsAvaliable implements ValidationRule
{
    protected int $bookId;

    public function __construct(int $bookId)
    {
        $this->bookId = $bookId;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $book = Book::find($this->bookId);

        if (!$book) {
            $fail('O livro não foi encontrado.');
            return;
        }

        if (!$book->status) {
            throw new \BadMethodCallException('O livro não possui o campo status');
        }

        if ($book->status !== BookStatus::Available->value) {
            $fail('O livro não está disponível para empréstimo.');
        }
    }
}
