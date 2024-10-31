<?php

namespace App\Actions\Loan;

use App\Dtos\Loan\DevolutionLoanDto;
use App\Enums\LoanStatus;
use App\Exceptions\Loan\BookAlreadyReturn;
use App\Models\Loan;

class DevolutionBookAction
{
    /**
     * @throws BookAlreadyReturn
     */
    public function execute(Loan $loan, DevolutionLoanDto $dto):Loan
    {
        if($loan->status === LoanStatus::DEVOLUTION){
            throw new BookAlreadyReturn('Livro já devolvido');
        }
        $loan->book->update(['status' => 'available']);
        $loan->user->update(['has_borrowed_books' => false]);
        $loan->status = LoanStatus::DEVOLUTION;
        $loan->update($dto->toArray());
        return $loan;
    }
}
