<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\TaskRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentTaskRepository::class
        );

        $this->app->bind(
            \App\Services\Interfaces\TaskServiceInterface::class,
            \App\Services\TaskService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
