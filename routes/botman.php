<?php
use App\Http\Controllers\BotManController;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use App\Http\Controllers\userController;
use App\Middleware\ReceivedMiddleware;
use App\Conversations\userEditConversation;
use App\Conversations\userNewConversation;
use App\Http\Controllers\createUserPSController;
use App\user;


$botman = resolve('botman');
$receivedmiddleware = new ReceivedMiddleware();
$botman->middleware->received($receivedmiddleware);

function isRegistered($bot){
    return $bot->getMessage()->getExtras('activeUser');
}

function gotoRegistration($bot){
        $keyboard = Keyboard::create()->type(Keyboard::TYPE_KEYBOARD)->
        addRow(
            KeyboardButton::create('START')->callbackData('START')
        )->toArray();
        return $bot->reply("*Это закрытый автоматизированный Телеграм бот.*\n\nВы не авторизованы. \n\nТолько сотрудники компании имеют право доступа с согласованием своих руководителей.\n\nДля того чтобы получить доступ, вам необходимо написать письмо со своей корпоративной почты (в копию своего руководителя) на почту ИТ отдела: admins@gencoindustry.com с указанием Telegram ID: ".$bot->getMessage()->getExtras('user_id'),array_merge($keyboard,['parse_mode'=>'markdown']));
}


$botman->hears('Hi', function ($bot) {
    if(isRegistered($bot)){
     $bot->reply("Hello");
    }
    else {
        gotoRegistration($bot);
    }
});

$botman->hears("/start|start", function($bot){
    if(isRegistered($bot)){
        $nameRus = user::where('telegram_id',$bot->getMessage()->getExtras('user_id'))->first()->nameRus;

        if($bot->getMessage()->getExtras('user_type')=='superadmin' || $bot->getMessage()->getExtras('user_type')=='hradmin'){
            $keyboard = Keyboard::create()->type(Keyboard::TYPE_KEYBOARD)
            ->addRow(
                KeyboardButton::create('Пользователи')->callbackData('Пользователи')
                )
            ->addRow(
                KeyboardButton::create('Редактировать')->callbackData('Редактировать')
                )
            ->addRow(
                KeyboardButton::create('Создать пользователя')->callbackData('Создать пользователя')
                )
            ->toArray();
        }
        else {
            $keyboard = Keyboard::create()->type(Keyboard::TYPE_KEYBOARD)
            ->addRow(
                KeyboardButton::create('Пользователи')->callbackData('Пользователи'))
            ->toArray();
        }
        $bot->reply($nameRus.", добро пожаловать в систему учета пользователей компании *ООО ГЕНДЖО*\n\nВыберите действие из меню ниже: ",array_merge(["parse_mode"=>"markdown"],$keyboard));
    }
    else {
        gotoRegistration($bot);
    }
});

$botman->hears("Пользователи", function($bot){
    if(isRegistered($bot)){
        $keyboard = Keyboard::create()->type(Keyboard::TYPE_INLINE)
        ->addRow(
            KeyboardButton::create('ЦО')->callbackData('Пользователи ЦО'),
            KeyboardButton::create('Волхов')->callbackData('Пользователи Волхов'))
        ->toArray();
        $bot->reply("Выберите каких пользователей отобразить:",$keyboard);
    }
    else {
        gotoRegistration($bot);
    }
});


$botman->hears("Создать пользователя", function($bot){
    $bot->startConversation(new userNewConversation);
});

$botman->hears("Редактировать", function($bot){
    $bot->startConversation(new userEditConversation);
});

$botman->hears("Пользователи ЦО", function($bot){
    if(isRegistered($bot)){
        $bot->reply("*ПОЛЬЗОВАТЕЛИ ЦЕНТРАЛЬНОГО ОФИСА*\n\n".userController::get_users_format1('co'), ["parse_mode"=>"markdown"]);
    }
    else {
        gotoRegistration($bot);
    }
});
$botman->hears("Пользователи Волхов", function($bot){
    if(isRegistered($bot)){
        $bot->reply("*ПОЛЬЗОВАТЕЛИ ОБЪЕКТА ВОЛХОВ:*\n\n".userController::get_users_format1('volhov'), ["parse_mode"=>"markdown"]);
    }
    else {
        gotoRegistration($bot);
    }
});

$botman->hears('Start conversation', BotManController::class.'@startConversation');


$botman->hears("Test", function($bot){
    $bot->reply(createUserPSController::template('co','$last_name_rus','$first_name_rus','$last_name_eng','$first_name_eng','$department','$position'));
});