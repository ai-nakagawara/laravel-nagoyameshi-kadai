<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'lowest_price',
        'highest_price',
        'postal_code',
        'address',
        'opening_time',
        'closing_time',
        'seating_capavity',
    ];

    //1つの店舗は1つのカテゴリーに属する
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
