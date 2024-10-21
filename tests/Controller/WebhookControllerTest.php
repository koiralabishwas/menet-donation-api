<?php

namespace Tests\Controller;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_webhook_controller(): void
    {
        $sig_header = 't=1729496594,v1=12499b2c97bc45e6856270688e020a132e1d795e41309b52de72fa307da4c00e,v0=84f50bec7e4a07893c15f950fbf45bd4d1441ca99bab092f8f2648fab77d39f9'; // Mock signature header
        $payload = [
            'id' => 'evt_test_webhook',
            'object' => 'event',
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_3QCF6fDSydiWZpHQ0pt4095P',
                    'object' => 'payment_intent',
                    'amount' => 123,
                    'currency' => 'jpy',
                    'metadata' => [
                        'type' => 'ONE_TIME',
                        'donor_name' => 'Bishwas Koirala',
                        'currency' => 'jpy',
                        'donation_project' => '高校進学ガイダンス',
                        'donor_id' => '4',
                        'amount' => '123',
                        'donor_external_id' => 'eb706ddc-8806-4afb-9825-976ccb70146e',
                    ],
                    'receipt_email' => 'wasubisu@gmail.com',
                ]
            ],
        ];

        $response = $this->postJson('/api/webhook', $payload, ['Stripe-Signature' => $sig_header]); // Correct header name

        $response->assertStatus(200);
        Log::info('Webhook processed successfully.');
    }
}
