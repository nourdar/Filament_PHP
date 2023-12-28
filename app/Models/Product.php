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

class Product extends Model implements Searchable
{
    use HasFactory;

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
        'quantity',
        'type',
        'published_at'
    ];

    protected $casts = [
        'images' => 'array',
        'options' => 'array',
        'mesures' => 'array',
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
        $url = 'product/'. $this->id;

        return new \Spatie\Searchable\SearchResult(
           $this,
           $this->name,
           $url,
        );
    }

}
