<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'image', 'price', 'product_categories_id'];
    protected $hidden  = ['created_at', 'updated_at', 'deleted_at', 'status'];
    protected $dates = ['created_at', 'updated_at'];

    public function productCategories() {
        return $this->belongsTo(ProductCategory::class, 'product_categories_id');
    }
}
