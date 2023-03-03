<?php

namespace App\Http\Responses;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class UserRegisteredResponse implements Responsable
{

    public function __construct(private User $user, private string $jwtToken) {

    }

    /**
     * @inheritDoc
     */
    public function toResponse($request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => [
                'user' => $this->user,
                'token' => $this->jwtToken,
                'type' => 'bearer',
            ]
        ]);
    }
}
