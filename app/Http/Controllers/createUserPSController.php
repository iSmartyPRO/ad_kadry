<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\branch;

class createUserPSController extends Controller
{
    public static function template($location_short_code,$last_name_rus,$first_name_rus,$last_name_eng,$first_name_eng,$department,$position){
        $branch = branch::where("shortcode",$location_short_code)->first();
        $script_content = '
        Function New-VolhovUser([string]$DisplayNameRus, [string]$LastName, [string]$FirstName, [string]$Pass, [string]$Title, [string]$Department) {
            $UserName = $FirstName + "." + $LastName
            $DisplayNameEng = $FirstName + " " + $LastName
            $UserPrincipalName = $UserName + "@gencoindustry.com"
            # Подключение к удаленному Exchange серверу с локального компьютера администратора
            Set-ExecutionPolicy RemoteSigned
            $UserCredential = Get-Credential
            $Session = New-PSSession -ConfigurationName Microsoft.Exchange -ConnectionUri http://exchange.gencoindustry.com/PowerShell/ -Authentication Kerberos -Credential $UserCredential
            Import-PSSession $Session -DisableNameChecking
            # Создание почтового ящика
            New-Mailbox `
                -Name  $DisplayNameEng `
                -UserPrincipalName $UserPrincipalName  `
                -Password (ConvertTo-SecureString -String $Pass -AsPlainText -Force) `
                -FirstName $FirstName `
                -LastName $LastName `
                -OrganizationalUnit "OU=Users,OU=Volhov,OU=Account,DC=gencoindustry,DC=com"
            # Назначение аттрибутов
            Set-ADUser `
                -Identity $UserName `
                -Replace @{ `
                    title=$Title; `
                    department=$Department; `
                    description=$DisplayNameRus; `
                    company="ООО ""ГЕНДЖО"""; `
                    physicalDeliveryOfficeName="Волхов (ГЕНДЖО)"; `
                    streetAddress="'.$branch->address.'"; `
                    l="г.Волхов" `
                }
            # Добавление основных групп пользователю
            Add-ADGroupMember -Identity "CN=mail_All GENCO,OU=Groups,OU=IT_Group,OU=Account,DC=gencoindustry,DC=com" -Members $UserName
            Add-ADGroupMember -Identity "CN=mail_All Volhov,OU=Groups,OU=IT_Group,OU=Account,DC=gencoindustry,DC=com" -Members $UserName
            # Добавление фото для пользователя из папки D:\scripts\photos\ с названием файла содержащее имя пользователя
            # Set-UserPhoto -Identity $DisplayNameEng -PictureData ([System.IO.File]::ReadAllBytes("D:\scripts\photos\" + $UserName + ".jpg")) –Confirm:$false
        }
        
        ##### КАСТОМИЗАЦИЯ ДАННЫХ
        New-VolhovUser `
            -DisplayNameRus "'.$last_name_rus.' '.$first_name_rus.'" `
            -LastName "Serapov" `
            -FirstName "Sadirbek" `
            -Pass "Ss06112019" `
            -Title "Оператор пк (Склад)" `
            -Department "Склад  (Волхов)"
        ';
        return $script_content;
    }
}
