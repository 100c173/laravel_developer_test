<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the product that the image belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to get only primary images.
     * 
     * Usage: ProductImage::primary()->get()
     *
     * @param $query
     * @return mixed
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to get only secondary images.
     * 
     * Usage: ProductImage::secondary()->get()
     *
     * @param $query
     * @return mixed
     */
    public function scopeSecondary($query)
    {
        return $query->where('is_primary', false);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }

}
