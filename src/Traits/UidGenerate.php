<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
trait UidGenerate{

    public static function uid($prefix = '', $length = 9, $where = null){
        $options = [
            'table' => (new static)->getTable(),
            'field' => 'uid',
            'length' => $length,
            'prefix' => $prefix,
        ];

        if($where){
            $options['where'] = $where;
        }

        return self::__generator($options);
    }

    public static function sku($prefix = '', $length = 9, $where = null){
        $options = [
            'table' => (new static)->getTable(),
            'field' => 'sku',
            'length' => $length,
            'prefix' => $prefix,
        ];

        if($where){
            $options['where'] = $where;
        }

        return self::__generator($options);
    }

    protected static function __generator($options){

        $field = $options['field'];
        $length = $options['length'] ?? 8;
        $prefix = $options['prefix'] ?? '';
        $query = DB::table($options['table'])->select($field);

        if(isset($options['where'])){

            foreach($options['where'] as $key => $value){
                $query->where($key, $value);
            }
        }

        $num = 1;
        $result = $query->orderByDesc('id')->first();

        if($result){
            $num = (int) filter_var($result->$field, FILTER_SANITIZE_NUMBER_INT) + 1;
        }


        return $prefix . str_pad($num, $length - strlen($prefix), "0", STR_PAD_LEFT);
    }

}
