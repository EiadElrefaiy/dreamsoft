<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $table = 'colors';
    protected $fillable = [
    'product_id',
    'color',
    'image'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pieces()
    {
        return $this->hasMany(Piece::class);
    }
   
}
