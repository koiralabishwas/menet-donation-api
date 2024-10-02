<?php

namespace App\Providers;

use App\Helpers\Helpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
use Stripe\StripeClient;
use Stripe\Webhook;

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
        $existing = StripeProvider::searchCustomerFromEmail($customerData['email']);

        if ($existing->data[0]->email) {
            return $existing->data[0];
        }
        $externalId = Helpers::createUuid();
        return $stripe->customers->create([
            'name' => $customerData['name'],
            'email' => $customerData['email'],
            'phone' => $customerData['phone'],
            'address' => [
                'country' => $customerData['address']['country'],
                'postal_code' => $customerData['address']['postal_code'],
                'city' => $customerData['address']['city'],
                'line1' => $customerData['address']['line1'],
                'line2' => $customerData['address']['line2'],
            ],
            'metadata' => ['donor_external_id' => $externalId],
        ]);
    }

    public static function searchCustomerFromEmail(string $email)
    {
        $stripe = app(StripeClient::class);
        return $stripe->customers->search([
            'query' => 'email:\'' . $email . '\''
        ]);
    }

    public static function deleteAllCustomers(): string
        // TODO: delete in production
    {
        $stripe = app(StripeClient::class);
        $count = 0;
        while ($count < 6) {
            $customers = $stripe->customers->all(['limit' => 100]);
            foreach ($customers as $customer) {
                try {
                    $stripe->customers->delete($customer->id);
                } catch (ApiErrorException $e) {
                    Log::error("failed $customer->id &&& $e" );
                }
            }

            $count = $count + 1;

        }
        return "all customers deleted";
    }

    public static function searchPrice(string $productId, int $amount)
    {
        $stripe = app(StripeClient::class);
        $existingPrice = $stripe->prices->search([
            'query' => "active:\"true\" AND product:\"$productId\" AND metadata[\"amount\"]:\"$amount\"",
        ]);
        if ($existingPrice->data) {
            return ($existingPrice->data[0]);
        }
        return null;
    }

    public static function createPrice(string $productId, int $amount): Price
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

    public static function createCheckoutSession(string $customerId, string $priceId): Session
    {
        $stripe = app(StripeClient::class);
        return $stripe->checkout->sessions->create([
            'success_url' => 'https://www.google.com',
            'ui_mode' => "hosted",
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1
            ]],
            'automatic_tax' => ['enabled' => false],
            'mode' => 'payment',
        ]);

    }


}
