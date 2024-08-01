<?php

namespace Jeanp\Jelper\Traits;

use DateTime;

trait Date
{

    public function sheet_date_to_php($excel_date, $format = 'Y-m-d')
    {
        if (!$excel_date) {
            return null;
        }

        if (is_string($excel_date)) {
            return $excel_date;
            //return Carbon::createFromFormat('d/m/Y',$excel_date)->format($format);
        }

        $unix_date = ($excel_date - 25569) * 86400;
        $excel_date = 25569 + ($unix_date / 86400);
        $unix_date = ($excel_date - 25569) * 86400;
        return gmdate($format, $unix_date);
    }

    public function daterange($value)
    {

        if (!$value) {
            return [];
        }

        $temp = explode(' - ', $value);

        $from =  DateTime::createFromFormat('d/m/Y', $temp[0]);
        $to = DateTime::createFromFormat('d/m/Y', $temp[1]);

        return [
            $from->format('Y-m-d') . ' 00:00:00',
            $to->format('Y-m-d') . ' 23:59:59'
        ];
    }

    function getMonths()
    {
        return [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];
    }
}
