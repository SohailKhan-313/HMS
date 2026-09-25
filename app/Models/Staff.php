<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    //
     protected $table = 'staff';  

    // Allow mass assignment
    protected $fillable = [
        'name',
        'email',
        'phone',
        'designation',
        'salary',
        'image'
    ];
}
