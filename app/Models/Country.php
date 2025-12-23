<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasTranslations;
    public $translatable = ['name'];

    protected $fillable = ['name'];

    public function cities(): HasMany{
        return $this->hasMany(City::class);
    }
}
