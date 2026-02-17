<?php

namespace Jeanp\Jelper\Datatables;

use Yajra\DataTables\EloquentDataTable;
use Illuminate\Support\Facades\DB;

class CustomEloquentDataTable extends EloquentDataTable
{
    public function withRelationColumn(string $relationPath, string $alias, array|string $fields = ['name'])
    {
        // Normalizar fields
        if (is_string($fields)) {
            $fields = [$fields];
        }

        // Tomar automáticamente el modelo base de la query
        $model = $this->getQuery()->getModel();
        $baseTable = $model->getTable();
        $relations = explode('.', $relationPath);

        $currentModel = $model;
        $previousTable = $baseTable;
        $chain = [];

        foreach ($relations as $relationName) {
            $relation = $currentModel->$relationName();
            $related = $relation->getRelated();

            $relatedTable = $related->getTable();
            $relatedConnection = $related->getConnectionName();
            $relatedDatabase = config("database.connections.$relatedConnection.database");

            $foreignKey = $relation->getForeignKeyName();
            $ownerKey = $relation->getOwnerKeyName();

            $chain[] = [
                'table'   => "$relatedDatabase.$relatedTable",
                'alias'   => $relatedTable,
                'foreign' => "$previousTable.$foreignKey",
                'owner'   => "$relatedTable.$ownerKey",
            ];

            $currentModel = $related;
            $previousTable = $relatedTable;
        }

        $last = end($chain);
        $finalAlias = $last['alias'];

        $fieldExpression = count($fields) > 1
            ? "CONCAT(" . collect($fields)
            ->map(fn($f) => "$finalAlias.$f")
            ->join(", ' ', ") . ")"
            : "$finalAlias.{$fields[0]}";

        $subQuery = $fieldExpression;

        foreach (array_reverse($chain) as $link) {
            $subQuery = "
                (select $subQuery
                 from {$link['table']} as {$link['alias']}
                 where {$link['owner']} = {$link['foreign']}
                 limit 1)
            ";
        }

        $subQuery = "COALESCE($subQuery, '')";

        // Agregar al SELECT
        $this->getQuery()->addSelect(DB::raw("$subQuery as $alias"));

        // Ordenar
        $this->orderColumn($alias, function ($query, $order) use ($subQuery) {
            $query->orderBy(DB::raw($subQuery), $order);
        });

        // Buscar
        $this->filterColumn($alias, function ($query, $keyword) use ($subQuery) {
            $query->whereRaw("$subQuery LIKE ?", ["%{$keyword}%"]);
        });

        return $this;
    }
}
