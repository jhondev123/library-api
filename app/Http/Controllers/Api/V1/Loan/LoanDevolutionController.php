<?php

namespace App\Http\Controllers\Api\V1\Loan;

use App\Actions\Loan\DevolutionBookAction;
use App\Dtos\Loan\DevolutionLoanDto;
use App\Exceptions\Loan\BookAlreadyReturn;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Loan\LoanResource;
use App\Models\Loan;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanDevolutionController extends Controller
{
    use HttpResponse;

    /**
     * Handle the incoming request.
     *
     * Este metodo lida com a devolução de um livro emprestado. Ele valida a requisição,
     * cria um DTO (Data Transfer Object) a partir dos dados validados e executa a ação de devolução.
     * Se a devolução for bem-sucedida, retorna uma resposta JSON com os dados do empréstimo atualizado.
     * Se o livro já foi devolvido, retorna uma mensagem de erro.
     *
     * Parâmetros esperados no corpo da requisição:
     * - devolution_date (required|date): Data da devolução do livro.
     * - observation (required|string): Observação sobre a devolução.
     *
     * @param Request $request A requisição contendo os dados da devolução.
     * @param Loan $loan O empréstimo a ser devolvido.
     * @param DevolutionBookAction $action A ação de devolução do livro.
     * @return JsonResponse Uma resposta JSON com os dados do empréstimo atualizado ou uma mensagem de erro.
     */
    public function __invoke(Request $request, Loan $loan, DevolutionBookAction $action)
    {
        if (!$request->has('devolution_date')) {
            $request->merge(['devolution_date' => now()]);
        }

        if (!$request->has('observation')) {
            $request->merge(['observation' => 'devolvido']);
        }
        $validation = Validator::make($request->all(), [
            'devolution_date' => 'required|date',
            'observation' => 'required|string',
        ]);

        if ($validation->fails()) {
            return $this->error('Erro ao devolver o livro', 422, $validation->errors());
        }

        $dto = DevolutionLoanDto::fromRequest($request->all());

        try {
            $loanUpdated = $action->execute($loan, $dto);
            return $this->response('Livro devolvido com sucesso', 200, new LoanResource($loanUpdated));
        } catch (BookAlreadyReturn $e) {
            return $this->error($e->getMessage(), 400);
        }

    }
}
