<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations;

    protected $fillable = ['country_id', 'name'];

    public array $translatable = ['name'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
