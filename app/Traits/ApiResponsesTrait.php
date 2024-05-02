<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponsesTrait
{
    /**
     * @param mixed $data Datos que se van a formatear a JSON
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

    public function unauthorizedResponse(string $message = 'Permisos insuficientes.'): JsonResponse
    {
        return response()->json([
            'error' => $message
        ], 403);
    }
}
