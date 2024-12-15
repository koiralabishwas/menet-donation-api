<?php

declare(strict_types=1);

namespace App\Enums;

enum WebhookSecret: string
{
    case LOCAL_WEBHOOK_SECRET = 'local_webhook_secret';
    case PAYMENT_INTENT_SUCCEED_SECRET = 'payment_intent_succeed_secret';
    case CUSTOMER_SUBSCRIPTION_CREATED_SECRET = 'customer_subscription_created_secret';
    case INVOICE_PAID_SECRET = 'invoice_paid_secret';

}
