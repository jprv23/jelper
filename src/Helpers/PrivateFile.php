<?php

namespace Jeanp\Jelper\Helpers;

use Illuminate\Support\Facades\Route;


class PrivateFile
{

    public static function routes($public_token = null)
    {

        Route::middleware('auth')->prefix('private')->name('private.')->group(function () {
            Route::get('stream/{url?}', [PrivateFile::class, 'stream'])->where(['url' => '.*'])->name('stream');
        });

        if ($public_token) {
            Route::prefix('privatetoken')->name('privatetoken.')->group(function () use ($public_token) {
                Route::get("stream-{$public_token}/url?}", [PrivateFile::class, 'stream'])->where(['url' => '.*'])->name('stream');
            });
        }
    }


    public function stream($url)
    {
        if (!$url)
            return null;

        $filePath = storage_path('app') . DIRECTORY_SEPARATOR . $url; // here in place of just using 'gallery', i'm setting it in a config file
        if (!file_exists($filePath)) {
            return abort('403', "El archivo que buscas no existe en el servidor");
        }

        return response()->file($filePath);
    }
}
