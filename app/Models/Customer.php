<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'linkedin',
        'facebook',
        'x-twitter',
        'address',
        'city',
        'state',
        'country',
        'company',
        'position',
    ];
}
