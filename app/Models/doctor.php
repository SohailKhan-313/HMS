<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class doctor extends Model
{
    //

    protected $fillable = [
        'name',
        'email',
        'phone',
        'speciality',
        'pmdc',
        'fee',
        'duty_days',
        'duty_start',
        'duty_end',
    ];
    public function appointments()
{
    return $this->hasMany(Appointment::class);
}
}
