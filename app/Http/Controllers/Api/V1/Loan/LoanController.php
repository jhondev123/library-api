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
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    use HttpResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::all()->load('fine');

        return $this->response('Empréstimos', 200, LoanResource::collection($loans));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, LoanBookAction $action)
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
            return $this->response('Empréstimo criado com sucesso', 201, new LoanResource($loan));

        } catch (BookUnavailableException|UserHasBorrowedBooksException $e) {
            return $this->error($e->getMessage(), 422);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load('fine');
        return $this->response('Empréstimo', 200, new LoanResource($loan));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
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

        return $this->response('Empréstimo atualizado com sucesso', 200, new LoanResource($loan));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();
        return response()->json(null, 204);
    }
}
