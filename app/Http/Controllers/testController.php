<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Adldap;

class testController extends Controller
{
    public function ad(){
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
        //dd($users);
        foreach ($users as $user) {
            echo $user->cn[0]."<br>";
            echo json_decode($user->info[0],true)['telegram_id']."<br><br>";
        }
    }
}
