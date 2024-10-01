<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Donation extends Model
{
    use HasFactory;

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
}
