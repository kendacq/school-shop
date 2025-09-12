<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
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

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
