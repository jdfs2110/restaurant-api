<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait GeneralClass
{
    public function successResponse($data, $message = ''): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ]);
    }

    public function errorResponse($error, $status = 404): JsonResponse {
        return response()->json([
            'error' => $error
        ], $status);
    }
}
