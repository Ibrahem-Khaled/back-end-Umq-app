<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderVendor extends Model
{ 
    protected $table = "order_vendor";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_product_id','provider_id','user_id','product_id','product_price','product_qty','provider_status','created','updated'
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
 
        'user_id' => "integer",
      
        'shipment_id' => "integer",
        'city_id' => "integer",
        'vat_price' => "decimal:2",
        'product_price' => "decimal:2",
       
        "order_product_id" => "integer",
        "product_qty" => "integer",
        
        'created' => 'timestamp',
        'updated' => 'timestamp',
    ];
 	
    use HasFactory;
}

