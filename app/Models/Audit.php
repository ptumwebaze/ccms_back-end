<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $guarded = [];

    use HasFactory;


    public function auditstaff()
    {
        return $this->belongsTo(Staff::class, 'addedby', 'id');
    }
}
