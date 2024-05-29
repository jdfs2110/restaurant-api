<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait CloudflareUtilsTrait
{
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
        return env('CLOUDFLARE_R2_URL') . '/' . $path;
    }
}
