<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI;

use FoleyBridgeSolutions\PracticeCsPI\Services\ActivityService;
use FoleyBridgeSolutions\PracticeCsPI\Services\ApiClient;
use FoleyBridgeSolutions\PracticeCsPI\Services\ClientService;
use FoleyBridgeSolutions\PracticeCsPI\Services\ContactService;
use FoleyBridgeSolutions\PracticeCsPI\Services\CustomFieldService;
use FoleyBridgeSolutions\PracticeCsPI\Services\EngagementService;
use FoleyBridgeSolutions\PracticeCsPI\Services\EntityService;
use FoleyBridgeSolutions\PracticeCsPI\Services\InteractionService;
use FoleyBridgeSolutions\PracticeCsPI\Services\InvoiceService;
use FoleyBridgeSolutions\PracticeCsPI\Services\LedgerService;
use FoleyBridgeSolutions\PracticeCsPI\Services\LinkService;
use FoleyBridgeSolutions\PracticeCsPI\Services\LookupService;
use FoleyBridgeSolutions\PracticeCsPI\Services\OfficeService;
use FoleyBridgeSolutions\PracticeCsPI\Services\PortalService;
use FoleyBridgeSolutions\PracticeCsPI\Services\ProjectService;
use FoleyBridgeSolutions\PracticeCsPI\Services\StaffService;
use FoleyBridgeSolutions\PracticeCsPI\Services\TagService;
use FoleyBridgeSolutions\PracticeCsPI\Services\TimeExpenseService;
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

        $this->app->singleton(ContactService::class, function ($app) {
            return new ContactService(
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

        $this->app->singleton(StaffService::class, function ($app) {
            return new StaffService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(ActivityService::class, function ($app) {
            return new ActivityService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(TimeExpenseService::class, function ($app) {
            return new TimeExpenseService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(ProjectService::class, function ($app) {
            return new ProjectService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(InteractionService::class, function ($app) {
            return new InteractionService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(PortalService::class, function ($app) {
            return new PortalService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(CustomFieldService::class, function ($app) {
            return new CustomFieldService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(OfficeService::class, function ($app) {
            return new OfficeService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(EntityService::class, function ($app) {
            return new EntityService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(TagService::class, function ($app) {
            return new TagService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(LinkService::class, function ($app) {
            return new LinkService(
                $app->make(ApiClient::class)
            );
        });

        $this->app->singleton(LookupService::class, function ($app) {
            return new LookupService(
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
