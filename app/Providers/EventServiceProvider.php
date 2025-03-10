<?php

namespace App\Providers;

use App\Events\OperationLogged;
use App\Listeners\LogOperation;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            App\Events\CreateBook::class => [App\Listeners\BookEventListener::class],
            App\Events\UpdateBook::class => [App\Listeners\BookEventListener::class],
            App\Events\DeleteBook::class => [App\Listeners\BookEventListener::class],
            OperationLogged::class => [
                LogOperation::class,
            ],
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
