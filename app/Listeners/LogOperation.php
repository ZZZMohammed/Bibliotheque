<?php

namespace App\Listeners;

use App\Events\OperationLogged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Operation;

class LogOperation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OperationLogged  $event
     * @return void
     */
    public function handle(OperationLogged $event)
    {
        Operation::create([
            'type' => $event->type,
            'table' => $event->table,
            'user_id' => $event->userId
        ]);
    }
}
