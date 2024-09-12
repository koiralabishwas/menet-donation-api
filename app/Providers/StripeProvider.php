<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class StripeProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(StripeClient::class,function(){
            return new StripeClient(env('STRIPE_SECRET_KEY'));
        });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public static function createCustomer(array $customerData)
    {
        $stripe = app(StripeClient::class);
        return $stripe->customers->create($customerData);
    }
}
