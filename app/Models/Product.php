<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
     use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_en',
        'title_ar',
        'slug',
        'description_en',
        'description_ar',
        'price',
    ];

    /**
     * Get all of the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the primary image for the product.
     */
    public function primaryImage(): HasOne
    {
        // This relationship will return the first image marked as primary.
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * The users that the product is assigned to.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'product_user');
    }
}
