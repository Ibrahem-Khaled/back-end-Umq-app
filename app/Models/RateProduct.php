<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateProduct extends Model
{ 
    protected $table = "rate_product";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
public function product()
{
return $this->hasOne(Product::class,'id','product_id')->select(['id','name_ar']);
}
public function users()
{
return $this->hasOne(Users::class,'id','user_id')->select(['id','name']);
}
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value','product_id','user_id','created_at'
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
         'user_id' => 'integer',
         'rate' => "decimal:2",

         'product_id'=> 'integer', 
'created_at' => 'datetime',
    ];

    use HasFactory;
}

