<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'item_id',
        'sku',
        'attributes',
        'price',
        'stock',
        'image_path',
        'alt_text'
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
