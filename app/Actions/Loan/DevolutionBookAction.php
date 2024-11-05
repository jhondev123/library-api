<?php

namespace App\Actions\Loan;

use App\Dtos\Loan\DevolutionLoanDto;
use App\Enums\LoanStatus;
use App\Exceptions\Loan\BookAlreadyReturn;
use App\Models\Loan;

/**
 * Class DevolutionBookAction
 *
 * Esta classe lida com a devolução de livros emprestados.
 *
 * @package App\Actions\Loan
 */
class DevolutionBookAction
{
    /**
     * Executa a ação de devolução de um livro emprestado.
     *
     * @param Loan $loan O empréstimo a ser devolvido.
     * @param DevolutionLoanDto $dto Os dados de devolução do empréstimo.
     * @return Loan Retorna o empréstimo atualizado.
     * @throws BookAlreadyReturn Se o livro já foi devolvido.
     */
    public function execute(Loan $loan, DevolutionLoanDto $dto): Loan
    {
        if ($loan->status === LoanStatus::CLOSED->value) {
            throw new BookAlreadyReturn('Livro já devolvido');
        }
        $loan->book->update(['status' => 'available']);
        $loan->user->update(['has_borrowed_books' => false]);
        $loan->status = LoanStatus::CLOSED->value;
        $loan->update($dto->toArray());
        return $loan;
    }
}
