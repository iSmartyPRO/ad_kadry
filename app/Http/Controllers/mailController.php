<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Adldap;
use Mail;


class mailController extends Controller
{
    public static function notify_about_request($data){
        Mail::send('emails.request_new_user',["data"=>$data], function($message){
            $message->from(env("MAIL_ADDRESS"),"GENCO Кадры");
            $message->to(env("MAIL_ADMIN_EMAIL"));
            $message->subject("Запрос на создание пользователя");
        }); 
    }
    public static function notify_user_created($data){
        Mail::send('emails.user_created',["data"=>$data], function($message){
            $message->from(env("MAIL_ADDRESS"),"GENCO Кадры");
            $message->to(env("MAIL_ADMIN_EMAIL"));
            $message->subject("Создан новый пользователь");
        }); 
    }
    public static function changed_mobile($data){
        Mail::send('emails.changed_mobile',["data"=>$data], function($message){
            $message->from(env("MAIL_ADDRESS"),"GENCO Кадры");
            $message->to(env("MAIL_ADMIN_EMAIL"));
            $message->subject("Изменен номер мобильного телефона");
        }); 
    }
    public static function changed_position($data){
        Mail::send('emails.changed_position',["data"=>$data], function($message){
            $message->from(env("MAIL_ADDRESS"),"GENCO Кадры");
            $message->to(env("MAIL_ADMIN_EMAIL"));
            $message->subject("Изменено название должности");
        }); 
    }


}
