<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Collection;

trait HttpResponse
{
    public function response(string $message, string|int $status, array|Model|JsonResource|Collection $data = [])
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data
        ], $status);
    }

    public function error(string $message, string|int $status, array|MessageBag $errors = [], array $data = [])
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'errors' => $errors,
            'data' => $data
        ], $status);
    }
}
