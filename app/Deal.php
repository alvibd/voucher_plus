<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function dealItems()
    {
        return $this->hasMany(DealItem::class);
    }
}
