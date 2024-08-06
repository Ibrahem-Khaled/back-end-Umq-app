<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryVideo extends Model
{ 
    protected $table = "gallery_video";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
public function provider()
{
return $this->hasOne(Provider::class,'id','provider_id')->select(['id','whats']);
}
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id','file', 'thump', "hidden",'published','created_at','updated_at'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
                 'block' => 'integer',
         'hidden'=> 'integer' ,
         'published'=> 'integer',
         
         'user_id' => 'integer',
         'rate' => "decimal:2",

         'product_id'=> 'integer', 
	 'provider_id'=> 'integer', 
     'created_at' => 'timestamp',
     'updated_at' => 'timestamp',
    ];

    use HasFactory;
}

