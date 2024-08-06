<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribeUser extends Model
{ 
    protected $table = "subscribe_user";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_name','user_phone','user_image',
        'package_id', 'package_name','package_period','package_allowed_product_numers','package_allowed_chat',
        //'start_date','expire_date',
        'price','by_admin','payment_method','payment_transaction_id','created','updated'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "user_id" => "integer",
        "package_id" => "integer",
        "price" => "integer",
        "by_admin" => "integer",
        "package_period" => "integer",
        "allowed_product_numers" => "integer",
        "package_allowed_chat" => "integer",
'start_date' => 'timestamp',
'expire_date' => 'timestamp',
'created' => 'timestamp',
'updated' => 'timestamp',
    ];

    use HasFactory;
}

