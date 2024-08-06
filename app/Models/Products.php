<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function provider()
    {
        return $this->belongsTo(Users::class, 'provider_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

}
