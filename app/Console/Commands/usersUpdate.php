<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Adldap\Adldap;
use App\user;

class usersUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users from Active Directory';

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
    public function get_ouUsers($ouDn){
        $ad = new Adldap();
        $config = [  
          'hosts'    => [env("AD_HOST")],
          'base_dn'  => $ouDn,
          'username' => env("AD_USER"),
          'password' => env("AD_PASS"),
        ];
        $ad->addProvider($config);
        $provider = $ad->connect();
        $users = $provider->search()->users()->get();
        return $users;

    }
    public function handle()
    {
        user::query()->truncate();
        $users_co = $this->get_ouUsers(env('AD_DN_CO'));
        foreach ($users_co as $user) {
            echo json_decode($user->info[0],true)['user_type'];
            user::create([
                'telegram_id'   => json_decode($user->info[0],true)['telegram_id'],
                'user_type'     => json_decode($user->info[0],true)['user_type'],
                'nameRus'       => $user->description[0],
                'nameEng'       => $user->displayName[0],
                'location'      => $user->physicalDeliveryOfficeName[0],
                'adLocation'    => 'co',
                'postalCode'    => $user->postalCode[0],
                'address'       => $user->streetAddress[0],
                'city'          => $user->l[0],
                'mobile'        => $user->mobile[0],
                'extention'     => $user->pager[0],
                'position'      => $user->title[0],
                'department'    => $user->department[0],
                'company'       => $user->company[0],
                'email'         => $user->userPrincipalName[0],
                ]);
            }
            $users_volhov = $this->get_ouUsers(env('AD_DN_VOLHOV'));
            foreach ($users_volhov as $user) {
                user::create([
                    'telegram_id'   => json_decode($user->info[0],true)['telegram_id'],
                    'user_type'    => json_decode($user->info[0],true)['user_type'],
                    'nameRus'       => $user->description[0],
                    'nameEng'       => $user->displayName[0],
                    'location'      => $user->physicalDeliveryOfficeName[0],
                    'adLocation'    => 'volhov',
                    'postalCode'    => $user->postalCode[0],
                    'address'       => $user->streetAddress[0],
                    'city'          => $user->l[0],
                    'mobile'        => $user->mobile[0],
                    'extention'     => $user->pager[0],
                    'position'      => $user->title[0],
                    'department'    => $user->department[0],
                    'company'       => $user->company[0],
                    'email'         => $user->userPrincipalName[0],
                    ]);
                }
    }
}