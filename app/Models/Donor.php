<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    // Fields that can be mass assigned
    protected $fillable = [
        "donor_external_id",
        "name",
        "email",
        "phone",
        "country_code",
        "postal_code",
        "address",
        "is_public",
        "display_name",
        "corporate_no",
        "message",
    ];

    // Guarded fields, usually auto-incrementing IDs and timestamps
    protected $guarded = [
        'donor_id',
        'created_at',
        'updated_at',
    ];



}
