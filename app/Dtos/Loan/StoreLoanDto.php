<?php

namespace App\Dtos\Loan;

class StoreLoanDto
{
    public function __construct(
        public string $book_id,
        public string $user_id,
        public string $status,
        public string $delivery_status,
        public ?string $loan_date,
        public ?string $return_date,
        public ?string $observation,
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            book_id: $data['book_id'],
            user_id: $data['user_id'],
            status: $data['status'],
            delivery_status: $data['delivery_status'],
            loan_date: $data['loan_date'] ?? now(),
            return_date: $data['return_date'] ?? now()->addDays(config('app.standart_return_time')),
            observation: $data['observation'] ?? null,

        );
    }

    public function toArray(): array
    {
        return [
            'book_id' => $this->book_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'delivery_status' => $this->delivery_status,
            'loan_date' => $this->loan_date,
            'return_date' => $this->return_date,
            'observation' => $this->observation,
        ];
    }
}
