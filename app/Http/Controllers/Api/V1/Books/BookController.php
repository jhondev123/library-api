<?php

namespace App\Http\Controllers\Api\V1\Books;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Books\BookResource;
use App\Models\Book;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->response('Livros',200,BookResource::collection(Book::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'required|string',
        ]);
        if($validation->fails()) {
            return $this->response('Erro ao cadastrar um livro',422,$validation->errors());
        }
        $book = Book::create($request->all());
        return $this->response('Livro Cadastrado com sucesso',200, new BookResource($book));
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return $this->response( 'Livro',200,new BookResource($book));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'required|string',
        ]);

        if($validation->fails()) {
            return $this->response('Erro ao editar o Livro',422,$validation->errors());
        }

        $book->update($request->all());
        return new BookResource($book);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(null, 204);

    }
}
