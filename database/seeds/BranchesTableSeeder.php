<?php

use Illuminate\Database\Seeder;
use App\branch;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        branch::create([
            "shortcode"     => "co",
            "name"          => "Центральный офис",
            "ad_dn"         => "OU=Users,OU=CO,OU=Account,DC=gencoindustry,DC=com",
            "postalCode"    => 197136,
            "city"          => "г. Санкт-Петербург",
            "address"       => "ул. профессора Попова 37, лит.В, офис 205",
            "mail_group1"   => "CN=mail_All GENCO,OU=Groups,OU=IT_Group,OU=Account,DC=gencoindustry,DC=com",
            "mail_group2"   => "CN=mail_All CO,OU=Groups,OU=IT_Group,OU=Account,DC=gencoindustry,DC=com"
        ]);
        branch::create([
            "shortcode"     => "volhov",
            "name"          => "Волхов",
            "ad_dn"         => "OU=Users,OU=Volhov,OU=Account,DC=gencoindustry,DC=com",
            "postalCode"    => 187403,
            "city"          => "г. Волхов",
            "address"       => "Кировский проспект 20",
            "mail_group1"   => "CN=mail_All GENCO,OU=Groups,OU=IT_Group,OU=Account,DC=gencoindustry,DC=com",
            "mail_group2"   => "CN=mail_All Volhov,OU=Groups,OU=IT_Group,OU=Account,DC=gencoindustry,DC=com"
        ]);
    }
}
