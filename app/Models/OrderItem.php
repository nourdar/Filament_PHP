<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'options',
        'unit_price'
    ];

    protected $casts = [
        'options' => 'array',
    ];


    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'order_id',
                'product_id',
                'quantity',
                'options',
                'unit_price'
            ]);
    }
}
