<?php

namespace Jeanp\Jelper\Helpers;

use App\Models\Log;
use App\Observers\GeneralObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LogHelper
{
    protected $table = 'logs';

    public static function init($except = [])
    {
        $modelsPath = app_path('Models');
        $files = File::allFiles($modelsPath);

        foreach ($files as $file) {
            $filename = $file->getFilename();

            //Excepciones
            $classParser = str_replace('.php','', $filename);
            if(in_array($classParser, $except)){
                continue;
            }

            $namespace = 'App\Models\\';
            $className = $namespace . pathinfo($filename, PATHINFO_FILENAME);

            if (class_exists($className) && is_subclass_of($className, \Illuminate\Database\Eloquent\Model::class)) {
                $className::observe(GeneralObserver::class);
            }
        }
    }
    /**
     * Handle the "created" event.
     */
    public function created($model)
    {
        $description = "Se ha creado un nuevo registro:\n";
        $fields = $model->getAttributes();
        $exclude_field = ['created_at', 'updated_at','password']; // Puedes excluir campos si es necesario

        foreach ($fields as $field => $value) {
            if (!in_array($field, $exclude_field)) {
                $description .= " - Campo: $field\n";
                $description .= "   Valor: " . (is_null($value) ? 'N/A' : $value) . "\n";
            }
        }

        DB::table($this->table)->insert([
            'model_type' => get_class($model),
            'action' => 'created',
            'model_id' => $model->id,
            'description' => $description,
            'user_id' => auth('web')->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Handle the "updated" event.
     */
    public function updated($model)
    {
        $changes = $model->getChanges();
        $description = "Se realizaron los siguientes cambios:\n";
        $exclude_field = ['updated_at','password'];
        $count_changes = 0;
        foreach ($changes as $field => $newValue) {
            if (!in_array($field, $exclude_field)) {
                $oldValue = $model->getOriginal($field);
                $description .= " - Campo: $field\n";
                $description .= "   Valor anterior: " . (is_null($oldValue) ? 'N/A' : $oldValue) . "\n";
                $description .= "   Nuevo valor: " . (is_null($newValue) ? 'N/A' : $newValue) . "\n";
                $count_changes++;
            }
        }
        if ($count_changes > 0) {
            DB::table($this->table)->insert([
                'model_type' => get_class($model), // Nombre del tipo de modelo
                'action' => 'updated',
                'model_id' => $model->id,
                'description' => $description,
                'user_id' => auth('web')->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted($model)
    {
        //
    }

    /**
     * Handle the "restored" event.
     */
    public function restored($model)
    {
        //
    }

    /**
     * Handle the "force deleted" event.
     */
    public function forceDeleted($model)
    {
        //
    }
}
