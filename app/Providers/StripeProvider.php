<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\StripeClient;

class StripeProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(StripeClient::class, function () {
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

    /**
     * Create a Stripe customer.
     *
     * @param array $customerData
     * @return Customer
     */
    public static function createCustomer(array $customerData): Customer
    {
        $stripe = app(StripeClient::class);
        return $stripe->customers->create($customerData);
    }


    public static function searchPrice(string $productId , int $amount)
    {
        $stripe = app(StripeClient::class);
        $existingPrice = $stripe->prices->search([
            'query' => "active:\"true\" AND product:\"$productId\" AND metadata[\"amount\"]:\"$amount\"",
        ]);
        if ($existingPrice->data){
            return ($existingPrice->data[0]);
        }
        return null;
    }

    public static function createPrice(string $productId , int $amount)
    {
        $stripe = app(StripeClient::class);

        $existingPrice = self::searchPrice($productId, $amount);

        if ($existingPrice) {
            return $existingPrice;
        }

        return $stripe->prices->create([
            'product' => $productId,
            'currency' => "jpy",
            'unit_amount' => $amount,
            'metadata' => ['amount' => $amount],
        ]);
    }

//    public static function createCheckoutSession($params) : Session {
//        $stripe = app(StripeClient::class);
//        return $stripe->checkout->sessions->create($params);
//    }
}
