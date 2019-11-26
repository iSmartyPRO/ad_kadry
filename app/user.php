<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $fillable = [
        'telegram_id', 
        'user_type', 
        'nameRus', 
        'nameEng', 
        'location', 
        'adLocation', 
        'postalCode', 
        'address', 
        'city', 
        'mobile', 
        'extention', 
        'position', 
        'department', 
        'company', 
        'email', 
        'password',
    ];
}
