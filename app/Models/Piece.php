<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    use HasFactory;
    protected $table = 'pieces';
    protected $fillable = [
    'color_id',
    'size',
    'price',
    'old_price',
    'delivery_fees',
    'quantity',
  ];

  public function favourites()
  {
      return $this->belongsToMany(User::class, 'favourites', 'piece_id', 'user_id');
  }

  public function users()
  {
      return $this->belongsToMany(User::class, 'cart', 'piece_id', 'user_id');
  }

  public function color()
  {
      return $this->belongsTo(Color::class);
  }

}
