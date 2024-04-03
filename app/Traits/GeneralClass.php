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
}
