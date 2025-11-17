<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\MergeSessionCart;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            MergeSessionCart::class,
        ],
    ];
}
