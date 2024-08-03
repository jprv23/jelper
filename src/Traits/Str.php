<?php

namespace Jeanp\Jelper\Traits;

use DateTime;
use Exception;

trait Str
{

    /**
     * Función para generar una cadena de texto aleatoria
     * @param length => Longitud de la cadena
     */
    public function rstring($length = 10)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function reemplazarTildes($cadena) {
        $originales = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ');
        $reemplazos = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N');
    
        $nuevaCadena = str_replace($originales, $reemplazos, $cadena);
    
        return $nuevaCadena;
    }

    function rangosSeSolapan($inicio1, $fin1, $inicio2, $fin2, $format = 'Y-m-d') {
        $inicio1 = DateTime::createFromFormat($format, $inicio1);
        $fin1 = DateTime::createFromFormat($format, $fin1);
        $inicio2 = DateTime::createFromFormat($format, $inicio2);
        $fin2 = DateTime::createFromFormat($format, $fin2);
    
        // Asegurarse de que el inicio no sea después del fin
        if ($inicio1 > $fin1 || $inicio2 > $fin2) {
            throw new Exception("La fecha de inicio no puede ser después de la fecha de fin.");
        }
    
        // Comprobar si los rangos se solapan
        if ($inicio1 <= $fin2 && $fin1 >= $inicio2) {
            return true;
        } else {
            return false;
        }
    }
    
    function rangosHorasSeSolapan($inicio1, $fin1, $inicio2, $fin2, $format = 'H:i:s') {
        $inicio1 = DateTime::createFromFormat($format, $inicio1);
        $fin1 = DateTime::createFromFormat($format, $fin1);
        $inicio2 = DateTime::createFromFormat($format, $inicio2);
        $fin2 = DateTime::createFromFormat($format, $fin2);
    
        // Asegurarse de que el inicio no sea después del fin
        if ($inicio1 > $fin1 || $inicio2 > $fin2) {
            throw new Exception("La hora de inicio no puede ser después de la hora de fin.");
        }
    
        // Comprobar si los rangos se solapan
        if ($inicio1 < $fin2 && $fin1 > $inicio2) {
            return true;
        } else {
            return false;
        }
    }
}
