<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\branch;

class createUserPSController extends Controller
{
    public static function mailGroups($location){
        $branch = branch::where("id",1)->first();
        $group_command = "";
        if($branch->mail_group1){
            $group_command .= 'Add-ADGroupMember -Identity "'.$branch->mail_group1.'" -Members $UserName'."\n";
        }
        if($branch->mail_group2){
            $group_command .= 'Add-ADGroupMember -Identity "'.$branch->mail_group2.'" -Members $UserName'."\n";
        }
        if($branch->mail_group3){
            $group_command .= 'Add-ADGroupMember -Identity "'.$branch->mail_group3.'" -Members $UserName'."\n";
        }
        return $group_command;
    }
    public static function create_script($last_name_rus,$first_name_rus,$last_name_eng,$first_name_eng,$department,$position,$location,$city,$postalCode,$address,$ou,$password){
        $script_content = '
        Function New-User([string]$DisplayNameRus, [string]$LastName, [string]$FirstName, [string]$Pass, [string]$Title, [string]$Department, [string]$location, [string]$city, [string]$postalCode, [string]$address) {
            $UserName = $FirstName + "." + $LastName
            $DisplayNameEng = $FirstName + " " + $LastName
            $UserPrincipalName = $UserName + "@'.env("AD_DOMAIN").'"
            # Подключение к удаленному Exchange серверу с локального компьютера администратора
            Set-ExecutionPolicy RemoteSigned
            $UserCredential = Get-Credential
            $Session = New-PSSession -ConfigurationName Microsoft.Exchange -ConnectionUri http://'.env("EXCHANGE_HOST").'/PowerShell/ -Authentication Kerberos -Credential $UserCredential
            Import-PSSession $Session -DisableNameChecking
            # Создание почтового ящика
            New-Mailbox `
                -Name  $DisplayNameEng `
                -UserPrincipalName $UserPrincipalName  `
                -Password (ConvertTo-SecureString -String $Pass -AsPlainText -Force) `
                -FirstName $FirstName `
                -LastName $LastName `
                -OrganizationalUnit "'.$ou.'"
            # Назначение аттрибутов
            Set-ADUser `
                -Identity $UserName `
                -Replace @{ `
                    title=$Title; `
                    department=$Department; `
                    description=$DisplayNameRus; `
                    company="'.env("COMPANY_NAME").'"; `
                    physicalDeliveryOfficeName=$location; `
                    streetAddress=$address; `
                    postalCode=$postalCode; `
                    l=$city `
                }
            # Добавление основных групп пользователю
            '.createUserPSController::mailGroups($location).'
            # Добавление фото для пользователя из папки D:\scripts\photos\ с названием файла содержащее имя пользователя
            # Set-UserPhoto -Identity $DisplayNameEng -PictureData ([System.IO.File]::ReadAllBytes("D:\scripts\photos\" + $UserName + ".jpg")) –Confirm:$false
        }
        
        ##### КАСТОМИЗАЦИЯ ДАННЫХ
        New-User `
            -DisplayNameRus "'.$last_name_rus.' '.$first_name_rus.'" `
            -LastName "'.$last_name_eng.'" `
            -FirstName "'.$first_name_eng.'" `
            -Pass "'.$password.'" `
            -Title "'.$position.'" `
            -Department "'.$department.'" `
            -location "'.$location.'" `
            -city "'.$city.'" `
            -address "'.$address.'" `
            -postalCode "'.$postalCode.'"
        ';
        return $script_content;
    }
}
