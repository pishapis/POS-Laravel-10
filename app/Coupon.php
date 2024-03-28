<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\ProductCategory;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'coupon_code', 'name', 'description',
        'product_category_id', 'expired',
        'status', 'discount'
    ];

    protected $hidden = [];

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])
        ->translatedFormat('l, d F Y');
    }
}
