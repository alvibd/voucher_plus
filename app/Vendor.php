<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Vendor extends Model
{
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function dealItems()
    {
        return $this->hasMany(DealItem::class);
    }
}
