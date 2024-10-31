<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static updateOrCreate(array $attributes, array $values)
 */
class Donor extends Model
{
    use HasFactory;

    protected $table = 'donors';

    protected $primaryKey = 'donor_id';

    // Primary key column name, if not 'id'
    protected $fillable = [
        'donor_external_id',
        'stripe_customer_id',
        'name',
        'name_furigana',
        'email',
        'phone',
        'country_code',
        'postal_code',
        'address',
        'is_public',
        'public_name',
        'corporate_no',
        'message',
        'stripe_customer_object',
    ];

    // Cast is_public as a boolean
    protected $casts = [
        'is_public' => 'boolean',
    ];
}
