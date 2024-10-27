<?php

namespace App\Http\Resources\Api\V1\Loan;

use App\Http\Resources\Api\V1\Books\BookResource;
use App\Http\Resources\Api\V1\Fine\FineResource;
use App\Http\Resources\Api\V1\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = ["open" => "Aberto", "closed" => "Fechado"];
        $delivery_status = ["late" => "Atrasado", "ok" => "Ok"];

        return [
            'id' => $this->id,
            'livro' => new BookResource($this->book),
            'usuario' => new UserResource($this->user),

            'data_emprestimo' => Carbon::parse($this->loan_date)->format('d/m/Y'),
            'data_previsao_devolucao' => Carbon::parse($this->return_date)->format('d/m/Y'),
            'data_devolucao' => $this->devolution_date ? Carbon::parse($this->devolution_date)->format('d/m/Y') : null,

            'status' => $status[$this->status],
            'status_entrega' => $delivery_status[$this->delivery_status],

            'multa' => $this->whenLoaded('fine', function () {
                return new FineResource($this->fine);
            }),
        ];
    }
}
