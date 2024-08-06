<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{ 
    protected $table = "provider";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
public function users()
{
return $this->hasOne(Users::class,'id','user_id')->select(['id','name', 'photo', "email", "country", "mobile",'role']);
}
public function city()
{
return $this->hasOne(City::class,'id','city_id')->select(['id','name_ar', 'name_en']);
}
public function organization()
{
return $this->hasOne(Organization::class,'id','org_id')->select(['id','name']);
}

public function rate()
{
    return $this->hasOne(RateProvider::class,'provider_id','id')->select(['value']);
  // return  55;
}

  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id','whats','city_id','org_id','created_at','updated_at'
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
         'provider_id' => 'integer',
         'user_id' => 'integer',
         'city_id' => 'integer',
         "org_id" => 'integer',
         'rate' => "decimal:2",
'created_at' => 'timestamp',
'updated_at' => 'timestamp',
    ];

    use HasFactory;
}

