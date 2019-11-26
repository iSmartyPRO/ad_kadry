<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class request_new_user extends Model
{
    //
    protected $fillable = [
        'adLocation',
        'last_name_rus',
        'first_name_rus',
        'last_name_eng',
        'first_name_eng',
        'department',
        'position',
        'status'
    ];
}
