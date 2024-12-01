<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
    'user_id',
    'status',
    'date',
    'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

        protected static function boot()
        {
            parent::boot();
    
            static::deleting(function($order) {
                // Delete associated items
                $order->items()->delete();
            });
        }
    
}
