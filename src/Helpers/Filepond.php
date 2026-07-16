<?php

namespace Jeanp\Jelper\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class Filepond
{

    public static function routes()
    {

        Route::post('fileponds/upload', [Filepond::class, 'upload']);
        Route::delete('fileponds/remove', [Filepond::class, 'remove']);
    }

    public function upload(Request $request)
    {

        try {

            $file = $request->file('filepond');
            $disk = $request->input('disk', 'public');

            if (!$file) {
                throw new Exception('No se encontró el archivo.');
            }

            $path = jelper()->saveFile(
                file: $file,
                directory: 'uploads',
                disk: $disk,
            );

            return resp()->success(
                message: 'Cargado correctamente.',
                data: [
                    'path' => $path,
                ],
            );
        } catch (Exception $e) {
            return resp()->catch($e);
        }
    }

    public function remove(Request $request)
    {
        try {

            $disk = $request->input('disk', 'public');

            $url = $request->getContent();
            $url = explode('storage/', $url);
            $url = end($url);

            $path = storage_path('app/' . $disk) . DIRECTORY_SEPARATOR . $url;

            unlink($path);

            return resp()->success(
                message: 'Eliminado correctamente.',
                data: [
                    'path' => $path,
                ]
            );
        } catch (Exception $e) {
            return resp()->catch($e);
        }
    }

    public static function getFiles($url, $multiple = false)
    {
        if (!$url) {
            return [];
        }

        $files = $multiple ? explode(',', $url) : [$url];
        $data = [];

        foreach ($files as $file) {
            $url = $multiple ? jelper()->getUrlFile($file) : $file;

            $data[] = [
                "source" => $url, // This should be the identifier of the file on your server
                "options" => [
                    "type" => 'local', // The type should be 'local' for preloaded files
                    "file" => [
                        "name" => getFilename($url), // The name of the file
                        "size" => getFileSize($url, true),
                    ],
                    "metadata" => [
                        "poster" => $url, // URL for a preview image
                        "downloadUrl" => $url,
                    ],
                ],
            ];
        }

        return $data;
    }
}
