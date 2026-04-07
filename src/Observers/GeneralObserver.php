<?php

namespace Jeanp\Jelper\Observers;

use Jeanp\Jelper\Helpers\LogHelper;

class GeneralObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created($user): void
    {
        $log_h=new LogHelper();
        $log_h->created($user);
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated($user): void
    {
        $log_h=new LogHelper();
        $log_h->updated($user);
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
