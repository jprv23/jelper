<?php

namespace Jeanp\Jelper\Traits;

trait Str
{

    public function random($length = 10)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function search($val)
    {
        return "%" . str_replace(" ", "%", $val) . "%";
    }

    public function reemplazarTildes($cadena) {
        $originales = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ');
        $reemplazos = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N');
    
        $nuevaCadena = str_replace($originales, $reemplazos, $cadena);
    
        return $nuevaCadena;
    }
}
