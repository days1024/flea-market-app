<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'brand',
    'description',
    'price',
    'condition',
    'image',
    'user_id'
];

    public function likes()
{
    return $this->hasMany(Like::class);
}

public function comments()
{
    return $this->hasMany(Comment::class);
}

public function categories()
{
    return $this->belongsToMany(Category::class, 'item_categories');
}
}
