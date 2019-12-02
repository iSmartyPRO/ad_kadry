<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Adldap;

class adUserController extends Controller
{
    public static function get_volhovUsers(){
        $ad = new Adldap();
        $config = [  
          'hosts'    => [env("AD_HOST")],
          'base_dn'  => 'OU=Users,OU=Volhov,OU=Account,DC=gencoindustry,DC=com',
          'username' => env("AD_USER"),
          'password' => env("AD_PASS"),
        ];
        $ad->addProvider($config);
        $provider = $ad->connect();
        $users = $provider->search()->users()->get();
        $user_list = "";
        $num = 1;
        foreach ($users as $user) {
            $user_list .= $num.". ".$user->cn[0]."\n";
            $num++;
        }
        return $user_list;
    }
    public static function get_coUsers(){
        $ad = new Adldap();
        $config = [  
          'hosts'    => [env("AD_HOST")],
          'base_dn'  => 'OU=Users,OU=CO,OU=Account,DC=gencoindustry,DC=com',
          'username' => env("AD_USER"),
          'password' => env("AD_PASS"),
        ];
        $ad->addProvider($config);
        $provider = $ad->connect();
        $users = $provider->search()->users()->get();
        $user_list = "";
        $num = 1;
        foreach ($users as $user) {
            $user_list .= $num.". ".$user->description[0]."\n";
            $num++;
        }
        return $user_list;
    }
    public static function updatePosition($user_cn,$position_new){
        $ad = new Adldap();
        $config = [  
          'hosts'    => [env("AD_HOST")],
          'base_dn'  => 'OU=Account,DC=gencoindustry,DC=com',
          'username' => env("AD_USER"),
          'password' => env("AD_PASS"),
        ];
        $ad->addProvider($config);
        $provider = $ad->connect();
        $user = $provider->search()->users()->find($user_cn);
        $user->title = $position_new;
        $user->save();
    }
    public static function updateMobile($user_cn,$mobile_new){
        $ad = new Adldap();
        $config = [  
          'hosts'    => [env("AD_HOST")],
          'base_dn'  => 'OU=Account,DC=gencoindustry,DC=com',
          'username' => env("AD_USER"),
          'password' => env("AD_PASS"),
        ];
        $ad->addProvider($config);
        $provider = $ad->connect();
        $user = $provider->search()->users()->find($user_cn);
        $user->mobile = $mobile_new;
        $user->save();
    }
}
