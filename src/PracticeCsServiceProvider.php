<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI;

use FoleyBridgeSolutions\PracticeCsPI\Services\ApiClient;
use FoleyBridgeSolutions\PracticeCsPI\Services\ClientService;
use FoleyBridgeSolutions\PracticeCsPI\Services\EngagementService;
use FoleyBridgeSolutions\PracticeCsPI\Services\InvoiceService;
use FoleyBridgeSolutions\PracticeCsPI\Services\LedgerService;
use Illuminate\Support\ServiceProvider;

class PracticeCsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/practicecs.php',
            'practicecs'
        );

        $this->app->singleton(ApiClient::class, function ($app) {
            return new ApiClient(
                config('practicecs.base_url'),
                config('practicecs.api_key')
            );
        });

        $this->app->singleton(ClientService::class, function ($app) {
            return new ClientService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(InvoiceService::class, function ($app) {
            return new InvoiceService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(LedgerService::class, function ($app) {
            return new LedgerService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(EngagementService::class, function ($app) {
            return new EngagementService(
                $app->make(ApiClient::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/practicecs.php' => config_path('practicecs.php'),
            ], 'practicecs-config');
        }
    }
}
