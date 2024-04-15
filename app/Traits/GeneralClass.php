<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
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

    private const UNDEFINED_PHOTO_URL = '/public/photo-undefined.png';

    function toBase64(string $name, string $folder): string
    {
        $path = "public/$folder/$name";

        $foto = Storage::exists($path) ?
            Storage::get($path) :
            Storage::get(self::UNDEFINED_PHOTO_URL);

        $type = pathinfo($path, PATHINFO_EXTENSION);

        return 'data:image/' . $type . ';base64,' . base64_encode($foto);
    }

    function deletePhotoIfExists(string $name, string $folder): void
    {
        $path = "public/$folder/$name";

        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
