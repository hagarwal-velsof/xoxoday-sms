<?php

namespace  Xoxoday\Sms;

use Illuminate\Support\ServiceProvider;

class SmsApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        if ($this->app->runningInConsole()) {
            // Publish assets
            $this->publishes([
              __DIR__.'/config/xosms.php' => config_path('xosms.php'),
              __DIR__.'/Jobs/SendSmsRequest.php' => app_path('Jobs\SendSmsRequest.php')
            ], 'sms_files');
        }
    }
}
