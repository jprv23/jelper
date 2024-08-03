<?php

namespace Jeanp\Jelper\Traits;

trait File
{

    public function getFileSize($file_path)
    {
        $size = filesize(storage_path('app/public') . DIRECTORY_SEPARATOR  . $file_path);

        return number_format($size / 1048576, 2);
    }

    public function saveFile($input, $directory, $disk = 'public', $filename = null)
    {

        if (is_string($input)) {
            $file = request()->file($input);
        } else {
            $file = $input;
        }

        if (!$file) {
            return null;
        }

        if (!$filename) {
            $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
        }

        return $file->storeAs($directory, $filename, $disk);
    }
}
