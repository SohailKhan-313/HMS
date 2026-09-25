<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class patienthistory extends Model
{
    //
        protected $table = 'patienthistory';

    protected $fillable = [
        'name',
        'age',
        'phone',
        'cnic',
        'due_amount',
        'wallet_amount',
    ];
}
