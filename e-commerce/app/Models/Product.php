<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
    'section_id',
    'seller_id',
    'description',
    'name',
    'image',
    'rate',
 ];

 public function seller()
 {
     return $this->belongsTo(Seller::class);
 }

 public function colors()
 {
     return $this->hasMany(Color::class);
 }


}
