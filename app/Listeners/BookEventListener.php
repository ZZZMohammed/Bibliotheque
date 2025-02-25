<?php

namespace App\Listeners;

use App\Events\CreateBook;
use App\Events\DeleteBook;
use App\Events\UpdateBook;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookEventListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event instanceof CreateBook) {
            Log::info('A book has been created.');
        } elseif ($event instanceof UpdateBook) {
            Log::info('A book has been updated.');
        } elseif ($event instanceof DeleteBook) {
            Log::info('A book has been deleted.');
        }
    }
}
