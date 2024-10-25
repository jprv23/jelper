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
    public function getUrlFile($path, $default = '', $disk = 'public')
    {

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
    
    function getFileSize($pathname, $bytes = false)
    {
        if (!$pathname) {
            return '';
        }

        $size = filesize($pathname);

        if ($bytes) {
            return $size;
        }

        $mb = $size / 1048576;

        if ($mb > 0.1) {
            return number_format($mb, 1) . " MB";
        }

        return number_format($mb * 1024) . " KB";
    }

    public function saveFile($file, $directory, $disk = 'public', $filename = null)
    {
        if (!$file) {
            return null;
        }

        if (!$filename) {
            $filename = time() . "_" . $this->getFilenameSanitaze($file->getClientOriginalName());
        }else{
            $filename = $this->getFilenameSanitaze($filename);
        }

        return $file->storeAs($directory, $filename, $disk);
    }

    public function getFilenameSanitaze($nombre) {
        // Reemplazar caracteres con acentos o especiales por sus equivalentes sin acento
        $nombre = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre);
    
        // Eliminar caracteres no permitidos: cualquier cosa que no sea letra, número, guión o guion bajo
        $nombre = preg_replace('/[^a-zA-Z0-9-_.]/', '', $nombre);
    
        // Convertir a minúsculas para mayor consistencia
        $nombre = strtolower($nombre);
    
        // Limitar la longitud del nombre (opcional)
        $nombre = substr($nombre, 0, 200);
    
        // Retornar el nombre limpio
        return $nombre;
    }
}
