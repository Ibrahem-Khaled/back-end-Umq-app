<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{ 
    protected $table = "chat_message";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text','image','video','recorder','status_read','deleted','senderId','receiverId', 'group_key', 'messageIdFollowed', 'reply', 'sender_wait_id', 'created_at','updated_at'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

        'deleted'=> 'integer', 
        'messageIdFollowed'=> 'integer', 
        'senderId' => 'integer',
        'receiverId' => 'integer',
        'sender_wait_id' => 'integer',
        "reply" => "integer",
        
'created_at' => 'timestamp',
'updated_at' => 'timestamp',
 
'block' => 'integer',
'hidden'=> 'integer' ,
'status'=> 'integer',
'user_id' => 'integer',
'rate' => "decimal:2",
"favorite" => "integer",
"city_id" => "integer",
"gallery_id" => "integer",
'product_id'=> 'integer', 
'provider_id'=> 'integer', 

    ];

    use HasFactory;
}

