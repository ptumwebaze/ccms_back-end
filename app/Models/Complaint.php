<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function forward()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }
    public function buz()
    {
        return $this->belongsTo(Business::class, 'business', 'id');
    }
}
