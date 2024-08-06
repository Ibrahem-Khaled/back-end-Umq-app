<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{ 
    protected $table = "sliders";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
public function provider()
{
return $this->hasOne(Provider::class,'id','provider_id')->select(['id','whats']);
}
public function product()
{
return $this->hasOne(Product::class,'id','product_id')->select(['id','name_ar']);
}
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image','object_type','provider_id','product_id','created_at','updated_at'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
                  'block' => 'integer',
         'hidden'=> 'integer' ,
         'status'=> 'integer',
         'provider_id' => "integer",
         'product_id' => "integer",
'created_at' => 'timestamp',
'updated_at' => 'timestamp',
    ];

    use HasFactory;
}

