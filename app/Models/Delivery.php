<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'wilaya_id',
        'commune_id',
        'wilaya_name',
        'commune_name',
        'is_wilaya',
        'is_commune',
        'is_center',
        'has_stop_desk',
        'is_deliverable',
        'center_id',
        'center_name',
        'center_address',
        'center_gps',
        'home',
        'zone',
        'desk',
        'retour',

    ];
}
