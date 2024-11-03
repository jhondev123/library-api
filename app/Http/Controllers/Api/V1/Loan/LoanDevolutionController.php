<?php

namespace App\Http\Controllers\Api\V1\Loan;

use App\Actions\Loan\DevolutionBookAction;
use App\Dtos\Loan\DevolutionLoanDto;
use App\Exceptions\Loan\BookAlreadyReturn;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Loan\LoanResource;
use App\Models\Loan;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanDevolutionController extends Controller
{
    use HttpResponse;

    /**
     * Handle the incoming request.
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
