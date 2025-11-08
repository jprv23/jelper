<?php

namespace Jeanp\Jelper\Traits;

use Illuminate\Support\Facades\DB;

trait UidGenerate
{

    public static function uid($prefix = '', $length = 9, $where = null)
    {
        $options = [
            'table' => (new static)->getTable(),
            'connection' => (new static)->getConnectionName(),
            'field' => 'uid',
            'length' => $length,
            'prefix' => $prefix,
        ];

        if ($where) {
            $options['where'] = $where;
        }

        return self::__generator($options);
    }

    public static function sku($prefix = '', $length = 9, $where = null)
    {
        $options = [
            'table' => (new static)->getTable(),
            'connection' => (new static)->getConnectionName(),
            'field' => 'sku',
            'length' => $length,
            'prefix' => $prefix,
        ];

        if ($where) {
            $options['where'] = $where;
        }

        return self::__generator($options);
    }

    protected static function __generator($options)
    {
        $field = $options['field'];
        $length = $options['length'] ?? 8;
        $prefix = $options['prefix'] ?? '';
        $query = DB::connection($options['connection'])->table($options['table'])->select($field);

        // Filtrar por prefijo
        $query->where($field, 'like', $prefix . '%');

        // Filtros extra opcionales
        if (isset($options['where'])) {
            foreach ($options['where'] as $key => $value) {
                $query->where($key, $value);
            }
        }

        // Obtener el último correlativo de esa serie
        $result = $query->orderByDesc('id')->first();

        $num = 1;
        if ($result && !empty($result->$field)) {
            // Extrae la parte numérica final después del prefijo
            if (preg_match('/^' . preg_quote($prefix, '/') . '(\d+)$/', $result->$field, $matches)) {
                $num = (int) $matches[1] + 1;
            }
        }

        // Genera el nuevo código
        $numericPart = str_pad($num, $length - strlen($prefix), '0', STR_PAD_LEFT);
        return $prefix . $numericPart;
    }
}
