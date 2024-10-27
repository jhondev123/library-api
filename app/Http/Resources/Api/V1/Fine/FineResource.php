<?php

namespace App\Http\Resources\Api\V1\Fine;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = ["open" => "Aberto", "closed" => "Fechado"];
       return [
            'id' => $this->id,
            'valor' => 'R$ '.number_format($this->value, 2, ',', '.'),
            'status' => $status[$this->status],
            'observacoes' => $this->observation,
            'ultima_atualizacao' => Carbon::parse($this->updated_at)->format('d/m/Y'),
       ];
    }
}
