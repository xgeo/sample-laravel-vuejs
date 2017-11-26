<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProductCategory;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'image', 'price', 'product_categories_id'];
    protected $hidden  = ['created_at', 'updated_at', 'deleted_at', 'status'];

    public function productCategories() {
        return $this->belongsTo(ProductCategory::class, 'product_categories_id');
    }
}
