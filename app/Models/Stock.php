<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;

class Stock extends Model
{
    use HasFactory;


    protected $fillable = [
        'product_id',
        'type',
        'quantity',
    ];



    // テーブル名をt_stocksに
    protected $table = 't_stocks';

    // public function image()
    // {
    //     return $this->belongsTo(Product::class);
    // }
}
