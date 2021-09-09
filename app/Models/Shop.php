<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class Shop extends Model
{
    use HasFactory;


    /**
     * お店を所有しているオーナーの取得
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
