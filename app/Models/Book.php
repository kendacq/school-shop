<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'book_id',
        'author',
        'publisher',
        'publish_date',
        'edition',
        'volume',
        'pages'
    ];

    protected $casts = [
        'publish_date' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
