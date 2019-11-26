<?php

namespace App\Middleware;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use App\user;


class ReceivedMiddleware implements Received
{
    public function received(IncomingMessage $message, $next, BotMan $bot){
        $user_id = $message->getSender();        
        $message->addExtras('user_id',$user_id);
        $user = user::where('telegram_id',$user_id)->first();
        if($user !== null){
            $message->addExtras('activeUser', true);
            if($user->user_type){
                $message->addExtras('user_type', $user->user_type);
            }
        }
        else {
            $message->addExtras('activeUser', false);
        }
        return $next($message);
    }
}