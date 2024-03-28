<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ProductCategory;

class Pembelian extends Model
{
    use SoftDeletes;

    protected $table = 'pembelian_produk';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_code', 'name','purchase_price', 'stock', 'category_id','tanggal','status'
    ];

    protected $hidden = [];

    // Relasi
    public function category() {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }
}
