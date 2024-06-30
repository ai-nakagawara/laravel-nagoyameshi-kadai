<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Reservation extends Model
{
    use HasFactory, Sortable;

    public function restaurant() {
        return $this->belongsTo(Restaurant::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
