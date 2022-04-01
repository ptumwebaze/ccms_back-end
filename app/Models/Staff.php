<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Staff extends Model
{

    use HasFactory,HasApiTokens;
    protected $guarded = [];

    public function staffadd()
    {
        return $this->belongsTo(User::class, 'addedby', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'staff_id', 'id');
    }


}
