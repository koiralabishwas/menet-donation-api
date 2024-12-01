<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $primaryKey = 'subscription_id';

    protected $fillable = [
        'subscription_external_id',
        'stripe_subscription_id',
        'donor_id',
        'donor_external_id',
        'donation_project',
        'amount',
        'currency',
        'stripe_subscription_object',
    ];

    protected $casts = [
        'is_cancelled' => 'boolean',
    ];
}
