<?php

namespace Tests\Controller;

use Tests\TestCase;

class CheckoutSessionControllerTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_create_success(): void
    {
        $donorData = [
            'customer' => [
                'name' => 'test',
                'email' => 'test@example.com',
                'phone' => '0123456789',
                'address' => [
                    'country' => 'JP',
                    'postal_code' => '100-0001',
                    'city' => '東京都',
                    'line1' => '東京都千代田区',
                    'line2' => '東京都千代田区千代田1-1-1',
                ],
                'is_public' => true,
                'display_name' => 'test',
                'corporate_no' => '1234567890',
                'message' => 'Hello World!',
            ],
            'product_id' => 'prod_Q2GxSqbpfzdba4',
            'price' => 1000,
        ];

        $response = $this->postJson('/api/checkout-session', $donorData);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'donor',
                'stripe_checkout_session',
                'stripe_price',
                'stripe_customer',
            ],
        ]);
        $response->assertJson([
            'status' => 201,
            'message' => 'success',
            'data' => [
                'donor' => [
                    'name' => $donorData['customer']['name'],
                    'email' => $donorData['customer']['email'],
                    'phone' => $donorData['customer']['phone'],
                    'country_code' => $donorData['customer']['address']['country'],
                    'postal_code' => $donorData['customer']['address']['postal_code'],
                    'is_public' => $donorData['customer']['is_public'],
                    'display_name' => $donorData['customer']['display_name'],
                    'corporate_no' => $donorData['customer']['corporate_no'],
                    'message' => $donorData['customer']['message'],
                    'stripe_customer_object' => [
                        'address' => $donorData['customer']['address'],
                    ],
                ],
            ],
        ]);

    }
}
