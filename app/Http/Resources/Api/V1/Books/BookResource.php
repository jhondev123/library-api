<?php

namespace App\Http\Resources\Api\V1\Books;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = [
            'available' => 'Disponível para empréstimo',
            'unavailable' => 'Indisponível para empréstimo',
        ];
        return [
            'id' => $this->id,
            'titulo' => $this->title,
            'autor' => $this->author,
            'status' => $status[$this->status],
            'descricao' => $this->description,
            'data_criacao' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'data_atualizacao' => Carbon::parse($this->updated_at)->format('d/m/Y'),
        ];
    }
}
