<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{ 
    protected $table = "order_product";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'provider_id', 'payment_method','payment_online_id','shipment_id','address_detail','city_id','country','product_price','vat_price','shipment_price','net','status_order','product_detail','created','updated'
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
        
        'user_id' => "integer",
 
    

        'shipment_id' => "integer",
        'city_id' => "integer",
        'vat_price' => "decimal:2",
        'product_price' => "decimal:2",
        'net' => "decimal:2",
        'shipment_price' => "decimal:2",

        'created' => 'timestamp',
        'updated' => 'timestamp',
    ];

    use HasFactory;
}

