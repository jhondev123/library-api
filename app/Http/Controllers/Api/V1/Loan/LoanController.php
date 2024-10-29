<?php

namespace App\Http\Controllers\Api\V1\Loan;

use App\Actions\Book\VerifyBookIsAvaliableAction;
use App\Actions\Loan\LoanBookAction;
use App\Actions\User\VerifyUserHasBorrowedBooksAction;
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
    public function store(Request $request,LoanBookAction $action)
    {
        $request->merge(['status' => 'open', 'delivery_status' => 'ok']);

        if(!$request->has('loan_date')) {
            $request->merge(['loan_date' => now()]);
        }
        if(!$request->has('return_date')) {
            $request->merge(['return_date' => now()->addDays(config('app.standart_return_time'))]);
        }
        $validation = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',

            'loan_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'devolution_date' => 'nullable|date',
            'observation' => 'nullable|string',
        ]);

        if($validation->fails()) {
            return $this->error('Erro ao criar um empréstimo',422,$validation->errors());
        }

        $book = Book::find($request->book_id);
        $user = User::find($request->user_id);

        return $action->execute($book,$user,$request->all());

    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load('fine');
        return $this->response('Empréstimo',200,new LoanResource($loan));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        //
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
