<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }
}
