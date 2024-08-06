<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavGalleryImage extends Model
{ 

    
    protected $table = "fav_gallery_image";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
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
        'user_id','gallery_id', 'favorite'
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
        "favorite" => "integer",
        "gallery_id" => "integer",
         'product_id'=> 'integer', 
	 'provider_id'=> 'integer', 
    ];

    use HasFactory;
}

