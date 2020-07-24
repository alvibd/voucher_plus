<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function dealItems()
    {
        return $this->belongsToMany(DealItem::class);
    }
}
