<?php

namespace App\Http\Responses;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class ApiSuccessResponse implements Responsable
{

    public function __construct(private string $message, private array $data) {
    }

    /**
     * @inheritDoc
     */
    public function toResponse($request)
    {
        return response()->json([
            'status' => 'success',
            'message' => $this->message,
            'data' => $this->data,
        ]);
    }
}
