<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class appointment extends Model
{
    //
     protected $table = 'appointments';

    protected $fillable = [
        'doctor_id',
        'name',
        'gender',
        'age',
        'phone',
        'time',
        'status'
    ];
    public function doctor()
{
    return $this->belongsTo(Doctor::class);
}
}
