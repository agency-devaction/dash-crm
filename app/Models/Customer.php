<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $linkedin
 * @property string $facebook
 * @property string $x-twitter
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $company
 * @property string $position
 */
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
