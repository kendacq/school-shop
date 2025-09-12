<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $fillable = [
        'item_id',
        'name',
        'type',
        'sku',
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
