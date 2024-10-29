<?php

namespace App\Actions\Loan;

use App\Actions\Book\VerifyBookIsAvaliableAction;
use App\Actions\User\VerifyUserHasBorrowedBooksAction;
use App\Http\Resources\Api\V1\Loan\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Routing\ResponseFactory;

class LoanBookAction
{
    use HttpResponse;

    public function __construct(
        private VerifyBookIsAvaliableAction $verifyBookIsAvaliableAction,
        private  VerifyUserHasBorrowedBooksAction $verifyUserHasBorrowedBooksAction,
    )
    {

    }
    public function execute(Book $book, User $user,array $data)
    {
        // verifica se o livro já está emprestado
        if(!$this->verifyBookIsAvaliableAction->execute($book)) {
            return $this->error('Livro não disponível para empréstimo',422);
        }

        // verifica se o usuário já tem livros emprestados
        if($this->verifyUserHasBorrowedBooksAction->execute($user)) {
            return $this->error('Usuário já tem livros emprestados',422);
        }
        $loan = Loan::create($data);

        $book->update(['status' => 'unavailable']);
        $user->update(['has_borrowed_books' => true]);
        return $this->response('Empréstimo criado com sucesso',201,new LoanResource($loan));

    }
}
