<?php

namespace App\Providers;

use App\Domain\EpubDownloader\Services\EpubDownloaderService;
use App\Domain\EpubDownloader\Services\Interfaces\EpubDownloaderServiceInterface;
use App\Domain\EpubMetadataCollector\Services\EpubMetadataCollectorService;
use App\Domain\EpubMetadataCollector\Services\Interfaces\EpubMetadataCollectorServiceInterface;
use App\Domain\EpubValidator\Services\EpubValidatorService;
use App\Domain\EpubValidator\Services\Interfaces\EpubValidatorServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EpubDownloaderServiceInterface::class, EpubDownloaderService::class);
        $this->app->bind(EpubMetadataCollectorServiceInterface::class, EpubMetadataCollectorService::class);
        $this->app->bind(EpubValidatorServiceInterface::class, EpubValidatorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
