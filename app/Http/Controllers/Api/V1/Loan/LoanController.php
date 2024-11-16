<?php

namespace App\Http\Controllers\Api\V1\Loan;

use App\Actions\Book\VerifyBookIsAvaliableAction;
use App\Actions\Loan\LoanBookAction;
use App\Actions\User\VerifyUserHasBorrowedBooksAction;
use App\Dtos\Loan\StoreLoanDto;
use App\Exceptions\Book\BookUnavailableException;
use App\Exceptions\Loan\UserHasBorrowedBooksException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Loan\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    use HttpResponse;

    /**
     *
     */
    /**
     * @return JsonResponse
     * Lista todos os empréstimos.
     * Este metodo retorna uma lista com todos os empréstimos cadastrados no sistema.
     * junto com as multas associadas a cada empréstimo.
     */
    public function index(): JsonResponse
    {
        $loans = Loan::all()->load('fine');

        return $this->response('Empréstimos', 200, LoanResource::collection($loans));
    }


    /**
     * Cria um novo empréstimo.
     *
     * Parâmetros esperados no corpo da requisição:
     * - book_id (required|exists:books,id): ID do livro a ser emprestado.
     * - user_id (required|exists:users,id): ID do usuário que está pegando o livro emprestado.
     * - loan_date (nullable|date): Data do empréstimo (opcional).
     * - return_date (nullable|date|after:today): Data de devolução prevista (opcional).
     * - devolution_date (nullable|date): Data de devolução efetiva (opcional).
     * - observation (nullable|string): Observações adicionais (opcional).
     *
     * @param Request $request Dados da requisição.
     * @param LoanBookAction $action Ação para realizar o empréstimo.
     * @return JsonResponse Retorna uma resposta JSON com uma mensagem de sucesso e o recurso criado,
     * ou uma mensagem de erro com os detalhes da validação.
     */
    public function store(Request $request, LoanBookAction $action): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'loan_date' => 'nullable|date',
            'return_date' => 'nullable|date|after:today',
            'devolution_date' => 'nullable|date',
            'observation' => 'nullable|string',
        ]);

        if ($validation->fails()) {
            return $this->error('Erro ao criar um empréstimo', 422, $validation->errors());
        }

        $book = Book::findOrFail($request->book_id);
        $user = User::findOrFail($request->user_id);

        $storeLoanDto = StoreLoanDto::fromRequest([
            ...$request->all(),
            'status' => 'open',
            'delivery_status' => 'ok',
        ]);
        try {
            $loan = $action->execute($book, $user, $storeLoanDto);

            Log::info('Empréstimo criado com sucesso', ['book_id' => $book->id, 'user_id' => $user->id]);

            return $this->response('Empréstimo criado com sucesso', 201, new LoanResource($loan));
        } catch (BookUnavailableException|UserHasBorrowedBooksException $e) {
            Log::error($e->getMessage(), ['book_id' => $book->id, 'user_id' => $user->id]);
            return $this->error($e->getMessage(), 422);
        }
    }


    /**
     * Exibe um empréstimo específico.
     *
     * @param Loan $loan O empréstimo a ser exibido.
     * @return JsonResponse Retorna uma resposta JSON com os detalhes do empréstimo.
     */
    public function show(Loan $loan): JsonResponse
    {
        $loan->load('fine');
        return $this->response('Empréstimo', 200, new LoanResource($loan));
    }

    /**
     * Atualiza um empréstimo existente.
     *
     * Parâmetros esperados no corpo da requisição:
     * - return_date (nullable|date|after_or_equal:today): Data de devolução efetiva (opcional).
     * - observation (nullable|string): Observações adicionais (opcional).
     *
     * @param Request $request Dados da requisição, incluindo os campos do empréstimo a serem atualizados.
     * @param Loan $loan O empréstimo a ser atualizado.
     * @return JsonResponse Retorna uma resposta JSON com uma mensagem de sucesso e o recurso atualizado,
     * ou uma mensagem de erro com os detalhes da validação.
     */
    public function update(Request $request, Loan $loan):JsonResponse
    {
        if (empty($request->all())) {
            return $this->response('', 204, []);
        }

        $validation = Validator::make($request->all(), [
            'return_date' => 'nullable|date|after_or_equal:today',
            'observation' => 'nullable|string',
        ]);

        if ($validation->fails()) {
            return $this->error('Erro ao atualizar um empréstimo', 422, $validation->errors());
        }

        $loan->update($request->all());
        Log::info('Empréstimo atualizado com sucesso', ['loan_id' => $loan->id]);

        return $this->response('Empréstimo atualizado com sucesso', 200, new LoanResource($loan));

    }

    /**
     * Remove um empréstimo.
     *
     * @param Loan $loan O empréstimo a ser removido.
     * @return JsonResponse Retorna uma resposta JSON vazia.
     */
    public function destroy(Loan $loan): JsonResponse
    {
        $loan->delete();
        Log::info('Empréstimo removido com sucesso', ['loan_id' => $loan->id]);
        return response()->json(null, 204);
    }
}
