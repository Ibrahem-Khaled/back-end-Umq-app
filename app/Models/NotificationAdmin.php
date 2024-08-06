<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationAdmin extends Model
{ 
    protected $table = "notification_admin";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'topic', "added_by",'title','message','fcm_status','created','updated', 'fcm_message_id', "fcm_status"
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
 
        'status'=> 'integer',
 
'created' =>'timestamp',
'updated' => 'timestamp',
'fcm_status'=> 'integer', 
    ];

    use HasFactory;
}

