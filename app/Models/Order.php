<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'customer_id',
        'order_number',
        'status',
        'tracking',
        'shipping_type',
        'shipping_price',
        'tracking',
        'is_free_shipping',
        'transport_provider',
        'notes',
        'total_price',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'customer_id',
                'order_number',
                'status',
                'tracking',
                'shipping_type',
                'shipping_price',
                'tracking',
                'transport_provider',
                'items',
                'is_free_shipping',
                'total_price',
                'notes'
            ]);
    }
}
