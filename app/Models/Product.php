<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GordonH\Reco4PHP\Facades\Reco4PHP;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];
    protected $table = 'products';
    protected $fillable = [
    'section_id',
    'seller_id',
    'description',
    'name',
    'image',
    'rate',
    'price',
 ];

     // Define which fields should be searchable
     public function toSearchableArray()
     {
         return [
             'id' => $this->id,
             'section_id' => $this->section_id,
             'seller_id' => $this->seller_id,
             'description' => $this->description,
             'name' => $this->name,
             'image' => $this->image,
             'rate' => $this->rate
         ];
     }
 

 public function section()
 {
     return $this->belongsTo(Section::class);
 }

 public function seller()
 {
     return $this->belongsTo(Seller::class);
 }

 public function colors()
 {
     return $this->hasMany(Color::class);
 }


}
