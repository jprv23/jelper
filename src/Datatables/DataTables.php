<?php

namespace Jeanp\Jelper\Datatables;

use Yajra\DataTables\Facades\DataTables as BaseDataTables;

class DataTables extends BaseDataTables
{
    public static function of($builder)
    {
        $original = parent::of($builder);

        // Si es EloquentDataTable, convertirlo a nuestra clase personalizada
        if ($original instanceof \Yajra\DataTables\EloquentDataTable) {
            return new CustomEloquentDataTable($original->getQuery());
        }

        return $original;
    }
}
