<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @method static create(array $all)
 */
class Donor extends Model
{
    use HasFactory;
    // Table name, if not following Laravel naming convention
    protected $table = 'donors';

    protected $primaryKey = 'donor_id';

    // Primary key column name, if not 'id'
    protected $fillable = [
        'donor_external_id',
        'name',
        'email',
        'phone',
        'country_code',
        'postal_code',
        'address',
        'is_public',
        'display_name',
        'corporate_no',
        'message',
    ];

    // Cast is_public as a boolean
    protected $casts = [
        'is_public' => 'boolean',
    ];



}
