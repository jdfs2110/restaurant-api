<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait GeneralClass
{
    /**
     * @param mixed $data Datos que se va a formatear a JSON
     * @param string $message Mensaje de respuesta (Por defecto una cadena vacía)
     * @param int $status El estado HTTP (Por defecto 200 OK)
     * @return JsonResponse La respuesta formateada a JSON
     */
    public function successResponse(mixed $data, string $message = '', int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $status);
    }

    /**
     * @param mixed $error El mensaje que se va a formatear
     * @param int $status El estado de error (Por 404 NOT FOUND)
     * @return JsonResponse La respuesta formateada a JSON
     */
    public function errorResponse(mixed $error, int $status = 404): JsonResponse
    {
        return response()->json([
            'error' => $error
        ], $status);
    }

    /**
     * @param string $error Mensaje de error genérico, ya que es una excepcion no contemplada
     * @return JsonResponse La respuesta formateada a JSON con status 500 INTERNAL SERVER ERROR
     */
    public function unhandledErrorResponse(string $error = 'Ha ocurrido un error.'): JsonResponse
    {
        return response()->json([
           'error' => $error
        ], 500);
    }

    /**
     * @param string $path Ruta donde está alojada la foto
     */
    function deletePhotoIfExists(string $path): void
    {
        if (Storage::disk('r2')->exists($path)) {
            Storage::disk('r2')->delete($path);
        }
    }

    private const PUBLIC_CLOUDFLARE_R2_STORAGE_URL = 'https://pub-bc3ca3a8662944629a67af74aa0a9f90.r2.dev/';

    /**
     * @param string $path Ruta donde está alojada la foto
     * @return string La URL formateada con el servidor de Cloudflare
     */
    function toCloudflareUrl(string $path): string
    {
        return self::PUBLIC_CLOUDFLARE_R2_STORAGE_URL . $path;
    }
}
