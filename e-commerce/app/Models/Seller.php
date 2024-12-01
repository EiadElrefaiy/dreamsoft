<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    protected $table = 'sellers';
    protected $fillable = [
    'name',
    'phone',
    'address',
    'password',
 ];

 public function products()
 {
     return $this->hasMany(Product::class);
 }

}
