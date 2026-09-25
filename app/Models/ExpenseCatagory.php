<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCatagory extends Model
{
    use HasFactory;

    protected $table = 'expense_catagory';

    protected $fillable = [
        'name',
        'description'
    ];
}