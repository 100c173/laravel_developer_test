<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;
use Str;

class Product extends Model
{
    use SoftDeletes, HasTranslations, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
    ];

    public $translatable = [
        'title',
        'description',
    ];

    /**
     * Get all of the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class); 
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
     * Automatically generate a unique slug
     * when creating a product.
     */
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (!empty($product->slug)) {
                return;
            }

            /**
             * Generate base slug from default locale.
             */
            $title = $product->getTranslation(
                'title',
                config('app.fallback_locale', 'en')
            );

            $baseSlug = Str::slug($title);
            $slug = $baseSlug;
            $counter = 1;

            /**
             * Ensure slug uniqueness at database level.
             * This loop is safe and fast due to indexed column.
             */
            while (
                static::where('slug', $slug)->exists()
            ) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            $product->slug = $slug;
        });
    }

    /**
     * Activity Log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'price',
                'slug',
            ])
            ->logOnlyDirty()
            ->useLogName('product');
    }
}
