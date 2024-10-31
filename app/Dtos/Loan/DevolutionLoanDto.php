<?php

namespace App\Dtos\Loan;

class DevolutionLoanDto
{
    public function __construct(
        public string $observation,
        public string $devolution_date,
    )
    {
    }

    public static function fromRequest($data):self
    {
        return new self(
            observation: $data['observation'],
            devolution_date: $data['devolution_date'],
        );

    }

    public function toArray():array
    {
        return [
            'observation' => $this->observation,
            'devolution_date' => $this->devolution_date,
        ];

    }

}
