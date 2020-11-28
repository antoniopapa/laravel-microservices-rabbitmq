<?php

namespace App\Providers;

use App\Jobs\AdminAdded;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \App::bindMethod(AdminAdded::class . '@handle', fn($job) => $job->handle());
    }
}
