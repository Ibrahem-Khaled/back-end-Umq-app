<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribePackage extends Model
{ 
    protected $table = "subscribe_package";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_en','name_ar','description_en','description_ar','price','period','allowed_product_numers','allowed_chat', "color",'hidden','created','updated'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price'=> 'integer',
        'hidden'=> 'integer',
        'period'=> 'integer',
        'allowed_product_numers'=> 'integer',
        'allowed_chat'=> 'integer',
        'created' => 'timestamp',
        'updated' => 'timestamp',
    ];

    use HasFactory;
}

