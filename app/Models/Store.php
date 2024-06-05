<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'location',
        'description',
    ];

    public function products() {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
