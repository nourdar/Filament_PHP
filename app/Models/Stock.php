<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'move_type',
        'product_name',
        'order_id',
        'order_item_id',
        'options',
        'qte',

    ];

    protected $casts = [
        'options' => 'array',
    ];



    public function product()
    {
        return $this->hasMany('App\Models\Product', 'id', 'id');
    }
}
