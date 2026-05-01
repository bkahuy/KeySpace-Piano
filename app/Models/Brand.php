<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model {
    protected $fillable = ['name', 'slug', 'logo_path', 'description'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
