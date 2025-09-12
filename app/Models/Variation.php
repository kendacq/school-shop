<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $fillable = [
        'name',
        'types',
        'sku',
        'stock',
        'image_path',
        'alt_text'
    ];
}
