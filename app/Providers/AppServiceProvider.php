<?php

namespace App\Providers;

use App\Contracts\CodeUniquenessChecker;
use App\Services\CodeCheckers\EloquentCodeUniquenessChecker;
use Illuminate\Support\ServiceProvider;
use Laravel\Prompts\Prompt;

class AppServiceProvider extends ServiceProvider
{
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
        Prompt::fallbackWhen(true);
    }
}
