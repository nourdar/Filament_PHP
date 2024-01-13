<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model implements Searchable
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'options',
        'mesures',
        'is_visible',
        'is_featured',
        'brand_id',
        'sku',
        'image',
        'images',
        'price',
        'old_price',
        'quantity',
        'type',
        'videos',
        'published_at',
        'translations'
    ];

    protected $casts = [
        'images' => 'array',
        'options' => 'array',
        'mesures' => 'array',
        'videos' => 'array',
        'translations' => 'array',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    public function mesures(): HasMany
    {
        return $this->hasMany(ProductMesure::class);
    }


    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('updated_at', 'desc');
        });
    }

    public function getSearchResult(): SearchResult
    {
        $url = 'product/' . $this->id;

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name,
            $url,
        );
    }


    public function stock()
    {
        return $this->belongsTo('App\Models\Stock');
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'slug',
                'description',
                'options',
                'mesures',
                'is_visible',
                'is_featured',
                'brand_id',
                'sku',
                'image',
                'images',
                'price',
                'old_price',
                'quantity',
                'type',
                'videos',
            ]);
    }
}
