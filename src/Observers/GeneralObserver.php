<?php

namespace Jeanp\Jelper\Observers;

use Jeanp\Jelper\Helpers\LogHelper;

class GeneralObserver
{

    protected $data = [];

    public function __construct()
    {
        $this->data = app()->bound('observer.data') ? app('observer.data') : [];
    }

    public function created($user): void
    {
        $data_created = $this->data;

        $log_h=new LogHelper();
        $log_h->created($user, $data_created);
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated($user): void
    {
        $data_updated = $this->data;

        $log_h=new LogHelper();
        $log_h->updated($user, $data_updated);
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted($user): void
    {
        //
    }

    /**
     * Handle the Model "restored" event.
     */
    public function restored($user): void
    {
        //
    }

    /**
     * Handle the Model "force deleted" event.
     */
    public function forceDeleted($user): void
    {
        //
    }
}
