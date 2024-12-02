<?php

namespace App\Providers;

use App\Helpers\EnvHelpers;
use App\Helpers\Helpers;
use App\Repositories\DonorRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
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
     */
    public static function createCustomer(array $customerData): object
    {
        $stripe = app(StripeClient::class);

        // db から取得して返したほうが確実。ストライプから取得すると、作成した直後は帰ってこない
        $existingDonor = DonorRepository::getDonorByEmail($customerData['email']);

        if ($existingDonor) {
            return $existingDonor;
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
            'metadata' => [
                'donor_external_id' => $externalId,
                'type' => $customerData['type'],
            ],
        ]);
    }

    public static function searchCustomerFromEmail(string $email)
    {
        $stripe = app(StripeClient::class);

        return $stripe->customers->search([
            'query' => 'email:\''.$email.'\'',
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
                    Log::error("failed $customer->id &&& $e");
                }
            }

            $count = $count + 1;

        }

        return 'all customers deleted';
    }

    public static function searchOneTimePrice(string $productId, int $amount)
    {
        $stripe = app(StripeClient::class);
        $existingPrice = $stripe->prices->search([
            'query' => "active:\"true\" AND product:\"$productId\" AND type:\"one_time\" AND metadata[\"amount\"]:\"$amount\"",
        ]);
        if ($existingPrice->data) {
            return $existingPrice->data[0];
        }

        return null;
    }

    public static function searchSubscriptionPrice(string $productId, int $amount)
    {
        $stripe = app(StripeClient::class);
        $existingPrice = $stripe->prices->search([
            'query' => "active:\"true\" AND product:\"$productId\" AND type:\"recurring\" AND metadata[\"amount\"]:\"$amount\"",
        ]);
        if ($existingPrice->data) {
            return $existingPrice->data[0];
        }

        return null;
    }

    public static function createOneTimePrice(string $productId, int $amount): Price
    {
        $stripe = app(StripeClient::class);

        $existingPrice = self::searchOneTimePrice($productId, $amount);

        if ($existingPrice) return $existingPrice;

        return $stripe->prices->create([
            'product' => $productId,
            'currency' => 'jpy',
            'unit_amount' => $amount,
            'metadata' => ['amount' => $amount],
        ]);
    }

    public static function createSubscriptionPrice(string $productId, int $amount)
    {
        $stripe = app(StripeClient::class);
        $existingPrice = self::searchSubscriptionPrice($productId, $amount);
        if ($existingPrice) {
            return $existingPrice;
        }

        return $stripe->prices->create([
            'product' => $productId,
            'currency' => 'jpy',
            'unit_amount' => $amount,
            'recurring' => ['interval' => 'month'],
            'metadata' => ['amount' => $amount],
        ]);
    }

    public static function searchPriceByPriceId(string $priceId)
    {
        $stripe = app(StripeClient::class);
        $price = $stripe->prices->retrieve($priceId);

        return $price;
    }

    public static function createCheckoutSession(string $customerId, string $priceId, $paymentIntentMetaData): Session
    {
        $stripe = app(StripeClient::class);
        $donor_name = json_encode($paymentIntentMetaData['donor_name']);
        $donor_email = json_encode($paymentIntentMetaData['donor_email']);
        $envHelper = new EnvHelpers;

        return $stripe->checkout->sessions->create([
            'success_url' => env('FRONT_END_URL')."/{$envHelper->getUrlByEnv('success')}?name={$donor_name}&email={$donor_email}",
            'cancel_url' => env('FRONT_END_URL')."/.{$envHelper->getUrlByEnv('cancel')}?name={$donor_name}",
            'ui_mode' => 'hosted',
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'automatic_tax' => ['enabled' => false],
            'mode' => 'payment',
            'payment_intent_data' => [
                'metadata' => $paymentIntentMetaData,
            ],
        ]);

    }

    public static function createSubscriptionSession(string $customerId, string $priceId, $subscriptionDataMetaData): Session
    {
        $stripe = app(StripeClient::class);
        $donor_name = json_encode($subscriptionDataMetaData['donor_name']);
        $donor_email = json_encode($subscriptionDataMetaData['donor_email']);
        $envHelper = new EnvHelpers;

        return $stripe->checkout->sessions->create([
            'success_url' => env('FRONT_END_URL')."/{$envHelper->getUrlByEnv('success')}?name={$donor_name}&email={$donor_email}",
            'cancel_url' => env('FRONT_END_URL')."/.{$envHelper->getUrlByEnv('cancel')}?name={$donor_name}",
            'ui_mode' => 'hosted',
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'automatic_tax' => ['enabled' => false],
            'mode' => 'subscription',
            'subscription_data' => [ // 注意: Subscription table に保存するため
                'metadata' => $subscriptionDataMetaData,
            ],
        ]);
    }

    public static function getProductNameFromId(string $productId): string
    {
        $stripe = app(StripeClient::class);
        $product = $stripe->products->retrieve($productId);

        return $product->name;

    }
}
