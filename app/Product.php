<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ProductCategory;
use App\Retur;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_code', 'photo', 'name', 'selling_price',
        'purchase_price', 'stock', 'category_id','tanggal'
    ];

    protected $hidden = [];

    // Relasi
    public function category() {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function returs() {
        return $this->hasMany(Retur::class);
    }
}
