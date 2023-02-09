<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Concrete\OrderRepository;
use App\Repository\Interface\OrderRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }
}
