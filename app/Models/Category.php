<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['name', 'slug', 'parent_id', 'description'];

    public function products() {
        return $this->hasMany(Product::class);
    }
    // Lấy danh mục cha
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    // Lấy các danh mục con
    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
