<?php

namespace App\Providers;

use App\Models\Repository;
use Laravel\Horizon\Horizon;
use App\Observers\RepositoryObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
        Repository::observe(RepositoryObserver::class);

        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Horizon::auth(function ($request) {
            if ($request->user()) {
                if (config('app.env') === 'local') {
                    return true;
                }
                return strtolower($request->user()->role) === 'admin';
            }
        });
    }
}
