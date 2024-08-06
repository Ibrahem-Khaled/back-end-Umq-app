<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{ 
    protected $table = "category";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_en','name_ar','description_en', "description_ar" ,'hidden','status','image','created_at','updated_at'
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
	 'provider_id'=> 'integer', 
'created_at' => 'timestamp',
'updated_at' => 'timestamp',
    ];

    use HasFactory;
}

