<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{ 
    protected $table = "product";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
public function provider()
{
return $this->hasOne(Provider::class,'id','provider_id')->select(['id','whats']);
}

public function category()
{
return $this->hasOne(Category::class,'id','category_id')->select(['id','name_ar', "name_en"]);
}
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_en',
        'description_ar',
        'price',
        'status',
        'hidden',
        'rent',
        'provider_id',
        'image',
        'created_at',
        'updated_at',
         'category_id'
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

         'provider_id'=> 'integer', 
         "price" => "integer",
        'category_id' => 'integer',
'created_at' => 'timestamp',
'updated_at' => 'timestamp',
    ];

    use HasFactory;
}

