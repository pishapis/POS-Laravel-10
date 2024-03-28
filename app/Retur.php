<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Product;

class Retur extends Model
{
    use SoftDeletes;

    protected $table = 'retur_barang';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_id', 'transaction_code','tanggal_retur', 'product_price', 'jumlah_retur'
    ];

    protected $hidden = [];

    // Relasi
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
