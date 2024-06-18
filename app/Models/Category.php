<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    //1つのカテゴリーは複数の店舗を作成できる
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function restaurant_ids() {
        return $this->belongsToMany(Restaurant::class)->withTimestamps();
    }
}
