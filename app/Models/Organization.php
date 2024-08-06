<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{ 
    protected $table = "organization";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','status','created_at','updated_at'
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
'created_at' => 'datetime',
'updated_at' => 'datetime',
    ];

    use HasFactory;
}

