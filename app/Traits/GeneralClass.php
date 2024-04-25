<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait GeneralClass
{
    public function successResponse($data, string $message = '', int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $status);
    }

    public function errorResponse($error, $status = 404): JsonResponse
    {
        return response()->json([
            'error' => $error
        ], $status);
    }

    public function unhandledErrorResponse($error = 'Ha ocurrido un error.'): JsonResponse
    {
        return response()->json([
           'error' => $error
        ], 500);
    }

    function deletePhotoIfExists(string $path): void
    {
        if (Storage::disk('r2')->exists($path)) {
            Storage::disk('r2')->delete($path);
        }
    }

    private const PUBLIC_CLOUDFLARE_R2_STORAGE_URL = 'https://pub-bc3ca3a8662944629a67af74aa0a9f90.r2.dev/';
    function toCloudflareUrl(string $path): string
    {
        return self::PUBLIC_CLOUDFLARE_R2_STORAGE_URL . $path;
    }
}
