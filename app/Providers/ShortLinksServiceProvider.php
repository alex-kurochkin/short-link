<?php

namespace App\Providers;

use App\Contracts\CodeUniquenessChecker;
use Illuminate\Support\ServiceProvider;

class ShortLinksServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            CodeUniquenessChecker::class,
            config('short-links.checker')
        );
    }

    public function boot(): void
    {
        //
    }
}
