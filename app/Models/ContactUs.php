<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{ 
    protected $table = "contact_us";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'subject','message','guest_name','guest_email','guest_phone','read_status'
       ,'user_id' ,'created_at','updated_at'

       // 'user_id', ,'created_at','updated_at'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
'created_at' => 'timestamp', //timestamp
'updated_at' => 'timestamp',
    ];

    use HasFactory;
}

