<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'note',
        'price',
        'status',
        'stock',
        'image_path',
        'alt_text',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function book()
    {
        return $this->hasOne(Book::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
