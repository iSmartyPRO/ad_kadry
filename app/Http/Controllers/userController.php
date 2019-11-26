<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;

class userController extends Controller
{
    public static function get_users_format1($adLocation){
        $users = user::where('adLocation',$adLocation)->orderBy('nameRus')->get();
        $result = '';
        foreach ($users as $user) {
            $result .= "*".$user->nameRus."*\n";
            $result .= "_".$user->nameEng."_\n";
            $result .= "Должность: ".$user->position."\n";
            $result .= $user->mobile ? "Мобильный: ".$user->mobile."\n" : "";
            $result .= $user->extention ? "Внутренний номер: ".$user->extention."\n" : "";
            $result .= "E-mail: ".$user->email."\n\n";
        }
        return $result;
    }
}
