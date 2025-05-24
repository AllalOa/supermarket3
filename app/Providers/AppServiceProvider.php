<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\NotificationService;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\OrderStatusUpdated::class => [
            \App\Listeners\StoreOrderStatusNotification::class,
        ],
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
        // Share notification data with supervisor layout
        View::composer('layouts.appp', function ($view) {
            if (auth()->check() && auth()->user()->role === 0) { // Assuming role 0 is supervisor
                $notificationService = new NotificationService();
                $view->with($notificationService->getSupervisorNotificationData());
            }
        });
    }
}
