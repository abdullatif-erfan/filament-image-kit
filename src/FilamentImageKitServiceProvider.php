<?php

namespace Abdullatif\FilamentImageKit;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentImageKitServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-image-kit')
            ->hasConfigFile()
            ->hasViews();
    }
}