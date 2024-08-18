<?php

namespace Jeanp\Jelper\Traits;
use Illuminate\Support\Facades\Storage;
trait File
{
    /**
     * Función para obtener la URL pública de un archivo
     * @param path => path original del archivo
     * @param default => en caso de imagen, definir una por defecto
     */
    public function getUrlFile($path, $default = '', $disk = 'public'){

        if (!$path) {
            if ($default) {
                return $default;
            }
            return '';
        }

        if (Storage::disk($disk)->exists($path)) {
            return asset(Storage::url($path));
        }

        if ($default) {
            return $default;
        }

        return '';
    }

    public function getFileSize($file_path)
    {
        $size = filesize(storage_path('app/public') . DIRECTORY_SEPARATOR  . $file_path);

        return number_format($size / 1048576, 2);
    }

    public function saveFile($file, $directory, $disk = 'public', $filename = null)
    {
        if (!$file) {
            return null;
        }

        if (!$filename) {
            $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
        }

        return $file->storeAs($directory, $filename, $disk);
    }
}
