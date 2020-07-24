<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealItem extends Model
{
    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
