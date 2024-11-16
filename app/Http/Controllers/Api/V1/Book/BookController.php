<?php

namespace App\Http\Controllers\Api\V1\Book;

use App\Enums\BookStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Books\BookResource;
use App\Models\Book;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class BookController
 * @package App\Http\Controllers\Api\V1\Book
 * @group Book
 * @authenticated
 * @resource Book
 * @apiResourceCollection App\Http\Resources\Api\V1\Books\BookResource
 * @apiResource App\Models\Book
 * Controller que manipular informações dos livros
 */
class BookController extends Controller
{
    use HttpResponse;

    /**
     * @return JsonResponse
     * Busca todos os livros
     */
    public function index()
    {
        return $this->response('Livros', 200, BookResource::collection(Book::all()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * Cadastra um livro
     *
     *  Parâmetros esperados no corpo da requisição:
     *  - title (required|string): Título do livro.
     *  - author (required|string): Autor do livro.
     *  - description (required|string): Descrição do livro.
     */
    public function store(Request $request): JsonResponse
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->error('Erro ao cadastrar um livro', 422, $validation->errors());
        }

        $bookValidated = $validation->validated();
        $bookValidated['status'] = BookStatus::Available->value;

        $book = Book::create($bookValidated);

        Log::info("Livro cadastrado: {$book->title} ({$book->author})");

        return $this->response('Livro Cadastrado com sucesso', 200, new BookResource($book));
    }

    /**
     * @param Book $book
     * @return JsonResponse
     * Busca um livro, passando o id do livro
     */
    public function show(Book $book)
    {
        return $this->response('Livro', 200, new BookResource($book));

    }


    /**
     * Atualiza um livro existente buscando-o pelo código.
     *
     *
     * Parâmetros esperados no corpo da requisição:
     * - title (nullable|string): Título do livro (opcional).
     * - author (nullable|string): Autor do livro (opcional).
     * - description (nullable|string): Descrição do livro (opcional).
     *
     * @param Request $request Dados da requisição, incluindo os campos do livro a serem atualizados.
     * @param Book $book Livro a ser atualizado.
     * @return JsonResponse Retorna uma resposta JSON com uma mensagem de sucesso e o recurso atualizado,
     * ou uma mensagem de erro com os detalhes da validação.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'title' => 'nullable|string',
            'author' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($validation->fails()) {
            return $this->error('Erro ao editar o Livro', 422, $validation->errors());
        }

        $book->update($validation->validated());
        Log::info("Livro atualizado: {$book->id} {$book->title} ({$book->author})");
        return $this->response('Livro atualizado com sucesso', 200, new BookResource($book));
    }

    /**
     * @param Book $book
     * @return JsonResponse
     * Deleta um livro
     */
    public function destroy(Book $book)
    {
        $book->delete();
        Log::info("Livro deletado: {$book->id} {$book->title}");
        return response()->json(null, 204);

    }
}
