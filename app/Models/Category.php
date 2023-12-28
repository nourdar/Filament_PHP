<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'parent_id',
        'is_visible',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function child(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
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
        $url = 'category/'. $this->id;

        return new \Spatie\Searchable\SearchResult(
           $this,
           $this->name,
           $url,
        //    $url
        );
    }
}
