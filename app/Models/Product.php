<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'store_id',
        'price',
        'description',
    ];

    public function stores() {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
