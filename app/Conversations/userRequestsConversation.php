<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Attachments\File;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use App\Http\Controllers\createUserPSController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\request_new_user;
use App\branch;
use App\Http\Controllers\mailController;

class userRequestsConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    protected $user_data;

    public function run()
    {
        $this->ask(Question::create("Хотите посмотреть список запросов на создание пользователей?")
            ->addButtons([
                Button::create("Да")->value("yes"), 
                Button::create("Нет")->value("no")]),
            function(Answer $answer){
                if($answer->getText() == "yes"){
                    $request_users = request_new_user::where("status","request")->get();
                    $user_list = "";
                    foreach ($request_users as $user) {
                        $user_list .= $user->id.". ".$user->last_name_rus." ".$user->first_name_rus."\n";
                    }
                    $this->say("Список запросов на создание пользователей: \n\n".$user_list);
                    $this->ask("Введите ID запроса для формирования полных данных о пользователе: ", function(Answer $answer){
                        //$this->say($answer->getText());
                        $user = request_new_user::where("id",$answer->getText())->first();
                        if($user){
                        $branch = branch::where("shortcode",$user->adLocation)->first();
                        $this->user_data = [
                            "last_name_rus" => $user->last_name_rus,
                            "first_name_rus" => $user->first_name_rus,
                            "last_name_eng" => $user->last_name_eng,
                            "first_name_eng" => $user->first_name_eng,
                            "department" => $user->department,
                            "position" => $user->position,
                            "location" => $branch->name,
                            "city" => $branch->city,
                            "postalCode" => $branch->postalCode,
                            "address" => $branch->address,
                            "ou" => $branch->ad_dn
                        ];
                        $user_info = 
                            "Фамилия (рус.): ".$this->user_data['last_name_rus'].",\n".
                            "Имя: (рус.): ".$this->user_data['first_name_rus'].",\n".
                            "Фамилия (анг.): ".$this->user_data['last_name_eng'].",\n".
                            "Имя: (анг.): ".$this->user_data['first_name_eng'].",\n".
                            "Отдел: ".$this->user_data['department'].",\n".
                            "Расположение: ".$this->user_data['location'].",\n".
                            "Адрес: \n".$this->user_data['city'].",\n".$this->user_data['postalCode'].", ".$this->user_data['address'].",\n".
                            "Дата запроса: ".$user->created_at;
                        $this->say($user_info);
                        $this->ask(Question::create('Верны ли данные? Продолжить?')->
                            addButtons([
                                Button::create('Да')->value('yes'),
                                Button::create('Нет')->value('no')
                            ]), function(Answer $answer){
                                if($answer->getText() == "yes"){
                                    $this->user_data["password"] = substr($this->user_data['last_name_eng'],0,1).strtolower(substr($this->user_data['last_name_eng'],0,1)).date('dmY');
                                    $script_content = createUserPSController::create_script($this->user_data['last_name_rus'],$this->user_data['first_name_rus'],$this->user_data['last_name_eng'],$this->user_data['first_name_eng'],$this->user_data['department'],$this->user_data['position'],$this->user_data['location'],$this->user_data['city'],$this->user_data['postalCode'],$this->user_data['address'],$this->user_data['ou'],$this->user_data['password']);
                                    $file_name = "create_user_".date("Y-m-d_H-i-s").".ps1";
                                    $file_url = env("APP_URL")."storage/scripts/".$file_name;
                                    Storage::disk('scripts')->put($file_name, mb_convert_encoding($script_content, "windows-1251","utf-8"));
                                    $this->say("Ваш скрипт для создания пользователя: ".$this->user_data['last_name_rus']." ".$this->user_data['first_name_rus'].": \n".$file_url);
                                    $this->ask(Question::create('Отправить письмо о созданном пользователе?')->
                                    addButtons([
                                        Button::create('Да')->value('yes'),
                                        Button::create('Нет')->value('no')
                                    ]), function(Answer $answer){
                                        if($answer->getText() == "yes"){
                                            $user_data = $this->user_data;
                                            mailController::notify_user_created((object)$user_data);
                                            $this->say("Письмо с данными для авторизации нового пользователя успешно отправлены");
                                        }
                                        elseif($answer->getText() == "no"){
                                            $this->say("Вы отменили операцию!");
                                        }
                                        else {
                                            $this->say("Неправильная команда. Начните заново.");
                                        }
                                    });

                                }
                                elseif($answer->getText() == "no"){
                                    $this->say("Вы отменили операцию!");
                                }
                                else {
                                    $this->say("Неправильная команда. Начните заново.");
                                }

                            });
                        }
                        else {
                            $this->say("Неправльный ID, попробуйте заново!");
                        }
                    }); }
                    elseif($answer->getText() == "no") {
                        $this->say("Вы отменили операцию!");
                    }
                    else {
                        $this->say("Неправильная команда. Начните заново.");
                    }
            });
    }
}
