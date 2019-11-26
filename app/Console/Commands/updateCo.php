<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Adldap\Adldap;

class updateCo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:updateCo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is script Update basic Data for Central Office Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $ad = new Adldap();
        $config = [  
          'hosts'    => [env("AD_HOST")],
          'base_dn'  => 'OU=Users,OU=co,OU=Account,DC=gencoindustry,DC=com',
          'username' => env("AD_USER"),
          'password' => env("AD_PASS"),
        ];
        $ad->addProvider($config);
        $provider = $ad->connect();
        $users = $provider->search()->users()->get();
        foreach ($users as $user) {
            $userUpd = $provider->search()->users()->find($user->cn[0]);
            $userUpd->streetAddress = 'ул. профессора Попова 37, лит.В, офис 205';
            $userUpd->l = 'г. Санкт-Петербург';
            $userUpd->physicalDeliveryOfficeName = 'Центральный офис';
            $userUpd->company = 'ООО "ГЕНДЖО"';
            $userUpd->postalCode = 197136;
            $userUpd->save();
        }

    
    }
}
