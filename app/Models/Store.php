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
        $this->hasMany(Product::class);
    }

    public function users() {
        $this->belongsTo(User::class);
    }
}
