<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'keywords',
        'email',
        'wilaya_depart',
        'telephone',
        'address',
        'transport',
        'logo',
        'slides',
        'head_code',
        'facebook_page',
        'twitter_page',
        'instagram_page',
        'pinterest_page',
    ];

    protected $casts = [
        'telephone' => 'array',
        'slides' => 'array',
        'transport' => 'array',
    ];

}
