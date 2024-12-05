<?php

namespace App\Providers;

use App\Enums\LogType;
use App\Exceptions\CustomException;
use App\Helpers\EnvHelpers;
use App\Helpers\Helpers;
use App\Repositories\DonorRepository;
use Exception;
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
     *
     * @throws CustomException
     */
    public static function createCustomer(array $customerData): object
    {
        try {
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
        } catch (Exception $e) {
            throw new CustomException(
                LogType::FATAL,
                __LINE__,
                $customerData['email'],
                'Failed to create Stripe customer',
                $e
            );
        }
    }

    /**
     * @throws CustomException
     */
    public static function searchCustomerFromEmail(string $email)
    {
        try {
            $stripe = app(StripeClient::class);

            return $stripe->customers->search([
                'query' => 'email:\''.$email.'\'',
            ]);
        } catch (Exception $e) {
            throw new CustomException(
                LogType::FATAL,
                __LINE__,
                $email,
                'Failed to search Stripe customer.',
                $e
            );
        }
    }

    /**
     * @throws CustomException
     */
    public static function deleteAllCustomers(): string
    {
        if (env('APP_ENV') === 'production') {
            return 'Production environment is not allowed to delete all customers';
        }

        try {
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
                $count++;
            }

            return $count.' customers deleted';
        } catch (Exception $e) {
            throw new CustomException(
                LogType::ERROR,
                __LINE__,
                '',
                'Failed to delete all Stripe customers',
                $e
            );
        }
    }

    /**
     * @throws CustomException
     */
    public static function searchOneTimePrice(string $productId, int $amount)
    {
        try {
            $stripe = app(StripeClient::class);
            $existingPrice = $stripe->prices->search([
                'query' => "active:\"true\" AND product:\"$productId\" AND type:\"one_time\" AND metadata[\"amount\"]:\"$amount\"",
            ]);
            if ($existingPrice->data) {
                return $existingPrice->data[0];
            }

            return null;
        } catch (Exception $e) {
            throw new CustomException(
                LogType::ERROR,
                __LINE__,
                '',
                'Failed to search Stripe one-time price',
                $e
            );
        }
    }

    /**
     * @throws CustomException
     */
    public static function searchSubscriptionPrice(string $productId, int $amount)
    {
        try {
            $stripe = app(StripeClient::class);
            $existingPrice = $stripe->prices->search([
                'query' => "active:\"true\" AND product:\"$productId\" AND type:\"recurring\" AND metadata[\"amount\"]:\"$amount\"",
            ]);
            if ($existingPrice->data) {
                return $existingPrice->data[0];
            }

            return null;
        } catch (Exception $e) {
            throw new CustomException(
                LogType::ERROR,
                __LINE__,
                '',
                'Failed to search Stripe subscription price',
                $e
            );
        }
    }

    /**
     * @throws CustomException
     */
    public static function createOneTimePrice(string $productId, int $amount): Price
    {
        try {
            $stripe = app(StripeClient::class);

            $existingPrice = self::searchOneTimePrice($productId, $amount);

            if ($existingPrice) {
                return $existingPrice;
            }

            return $stripe->prices->create([
                'product' => $productId,
                'currency' => 'jpy',
                'unit_amount' => $amount,
                'metadata' => ['amount' => $amount],
            ]);
        } catch (Exception $e) {
            throw new CustomException(
                LogType::FATAL,
                __LINE__,
                '',
                'Failed to create Stripe one-time price',
                $e
            );
        }
    }

    /**
     * @throws CustomException
     */
    public static function createSubscriptionPrice(string $productId, int $amount)
    {
        try {
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
        } catch (Exception $e) {
            throw new CustomException(
                LogType::FATAL,
                __LINE__,
                '',
                'Failed to create Stripe subscription price',
                $e
            );
        }
    }

    /**
     * @throws CustomException
     */
    public static function searchPriceByPriceId(string $priceId)
    {
        try {
            $stripe = app(StripeClient::class);
            $price = $stripe->prices->retrieve($priceId);

            return $price;
        } catch (Exception $e) {
            throw new CustomException(
                LogType::ERROR,
                __LINE__,
                '',
                'Failed to search Stripe price',
                $e
            );
        }
    }

    /**
     * @throws CustomException
     */
    public static function createCheckoutSession(string $customerId, string $priceId, $paymentIntentMetaData): Session
    {
        try {
            $stripe = app(StripeClient::class);
            $donor_name = json_encode($paymentIntentMetaData['donor_name']);
            $donor_email = json_encode($paymentIntentMetaData['donor_email']);
            $envHelper = new EnvHelpers;

            return $stripe->checkout->sessions->create([
                'success_url' => "/{$envHelper->getUrlByEnv('success')}?name={$donor_name}&email={$donor_email}",
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
        } catch (Exception $e) {
            throw new CustomException(
                LogType::FATAL,
                __LINE__,
                $paymentIntentMetaData['donor_email'],
                'Failed to create Stripe checkout session',
                $e
            );
        }

    }

    /**
     * @throws CustomException
     */
    public static function createSubscriptionSession(string $customerId, string $priceId, $subscriptionDataMetaData): Session
    {
        try {
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
        } catch (Exception $e) {
            throw new CustomException(
                LogType::FATAL,
                __LINE__,
                $subscriptionDataMetaData['donor_email'],
                'Failed to create Stripe checkout session',
                $e
            );
        }
    }

    /**
     * @throws CustomException
     */
    public static function getProductNameFromId(string $productId): string
    {
        try {
            $stripe = app(StripeClient::class);
            $product = $stripe->products->retrieve($productId);

            return $product->name;
        } catch (Exception $e) {
            throw new CustomException(
                LogType::FATAL,
                __LINE__,
                '',
                'Failed to get Stripe product name',
                $e
            );
        }
    }
}
