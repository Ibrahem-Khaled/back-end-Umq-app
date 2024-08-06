<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{ 
    protected $table = "shipment";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name_ar','name_en','status'


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
        
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

    use HasFactory;
}

