<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'filename',
    ];

    /**
     * Imageを所有しているオーナーの取得
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

}
