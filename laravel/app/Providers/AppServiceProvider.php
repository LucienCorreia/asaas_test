<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Integrations\Asaas\Abstract\Asaas;
use App\Integrations\Asaas\AsaasImplementation;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Asaas::class, function () {
            return new AsaasImplementation(
                new Client([
                    'base_uri' => 'https://api-sandbox.asaas.com/',
                    'headers' => [
                        'access_token' =>  config('integrations.asaas.token'),
                        'content-type' => 'application/json',
                        'accept' => 'application/json',
                    ]
                ])
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
