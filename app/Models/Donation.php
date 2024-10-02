<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Donation extends Model
{
    use HasFactory;

//    protected $guarded = ['donor_external_id'];

    protected $table = 'donations';

    protected $primaryKey = 'donation_id';

    protected $fillable = [
        "donation_external_id",
        "donor_id",
        "donor_external_id",
        "subscription_external_id",
        "stripe_subscription_id",
        "donation_project",
        "amount",
        "currency",
        "tax_deduction_certificate",
        "stripe_donation_object",


    ];

    public static function boot(): void
    {
        parent::boot();

        static::updating(function ($model) {
            // Check if donor_external_id is being changed
            if ($model->isDirty('donor_external_id')) {
                // Prevent updating donor_external_id if it is already set
                if ($model->getOriginal('donor_external_id') !== null) {
                    throw new Exception('The donor_external_id field can only be set once.');
                }
            }
        });
    }
}
