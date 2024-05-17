<?php

namespace App\Providers;

use App\Services\Implements\Auth\LoginService;
use App\Services\Implements\Auth\LogoutService;
use App\Services\Implements\Auth\RegisterService;
use App\Services\Implements\TaskService;
use App\Services\Interfaces\Auth\LoginServiceInterface;
use App\Services\Interfaces\Auth\LogoutServiceInterface;
use App\Services\Interfaces\Auth\RegisterServiceInterface;
use App\Services\Interfaces\TaskServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public array $singletons = [
        // Auth services
        LoginServiceInterface::class => LoginService::class,
        LogoutServiceInterface::class => LogoutService::class,
        RegisterServiceInterface::class => RegisterService::class,

        // ETC
        TaskServiceInterface::class => TaskService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
