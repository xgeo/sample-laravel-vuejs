<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class ProductCategory extends Model
{
    protected $fillable = ['display'];

    public function products() {
        return $this->hasMany(Product::class, 'product_categories_id');
    }
}
