<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ApiFailureResponse implements Responsable
{
    public function __construct(private string $message)
    {
    }

    public function toResponse($request)
    {
        return response()->json([
            'status' => 'error',
            'message' => $this->message,
            'data' => []
        ]);
    }
}
