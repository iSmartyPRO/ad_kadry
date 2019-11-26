<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class branch extends Model
{
    //
    protected $table = 'branches';

    protected $fillable = ['shortcode','name','ad_dn','address','city'];

}
