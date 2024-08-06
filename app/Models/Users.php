<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Tymon\JWTAuth\Contracts\JWTSubject;


class Users extends Authenticatable implements JWTSubject
{
    protected $table = "users";
    public $timestamps = false;
    public $boolean = 1;
    protected $primaryKey = 'id';
    protected $guarded = [];
    use HasFactory, Notifiable;


    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id')->select(['id', 'name_ar', 'name_en']);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'block' => 'integer',
        'hidden' => 'integer',
        'status' => 'integer',
        'user_id' => 'integer',
        'rate' => "decimal:2",

        'provider_id' => 'integer',
        "price" => "integer",
        'category_id' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',

        "city_id" => "integer"

    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }


    public function products()
    {
        return $this->hasMany(Products::class, 'provider_id');
    }
}
