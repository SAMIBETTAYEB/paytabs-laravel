<?php

namespace Damas\Paytabs;

use Illuminate\Support\ServiceProvider;

class PaytabsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../config/paytabs.php' => config_path('paytabs.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../../config/paytabs.php', 'paytabs'
        );

        $this->app->singleton(Paytabs::class, function() {
			return new Paytabs(config('paytabs.merchant_email', ''), config('paytabs.secret_key', ''));
        });
    }
}
