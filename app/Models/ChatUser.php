<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{ 
    protected $table = "chat_user";
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $boolean = 1;
    
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_a','person_b','group_key','group_type','lastMessageTime'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
'lastMessageTime' => 'timestamp',
"blocker_person_a" => "integer",
"blocker_person_b" => "integer",
"blocker_admin" => "integer",
"person_a" => "integer",
"person_b" => "integer",

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

