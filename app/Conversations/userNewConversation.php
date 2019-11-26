<?php

namespace App\Conversations;

use ___PHPSTORM_HELPERS\object;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use App\Http\Controllers\adUserController;
use App\request_new_user;


class userNewConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    protected $user;
    public function run()
    {
        $this->say("Эта опция позволит вам создать запрос на создание пользователя и электронной почты.");
        $this->ask(Question::create("Вы действительно хотите продолжить?")
        ->addButtons([
            Button::create('Да')->value('continue_yes'),
            Button::create('Нет')->value('continue_no')]), 
        function(Answer $answer){
            if($answer->getText() == 'continue_yes'){
                $this->ask(Question::create("Выберите объект, куда будет зарегестрирован пользователь")
                    ->addButtons([
                        Button::create("ЦО")->value('co'),
                        Button::create("Волхов")->value('volhov'),
                    ]), function(Answer $answer){
                            $this->user['location'] = $answer->getText();
                            $this->ask("Введите Фамилию на русском языке:", function(Answer $answer){
                                $this->user['last_name_rus'] = $answer->getText();
                                $this->ask("Введите Имя на русском языке:", function(Answer $answer){
                                    $this->user['first_name_rus'] = $answer->getText();
                                    $this->ask("Введите Фамилию на английском языке:", function(Answer $answer){
                                        $this->user['last_name_eng'] = $answer->getText();
                                        $this->ask("Введите имя на английском языке:", function(Answer $answer){
                                            $this->user['first_name_eng'] = $answer->getText();
                                            $this->ask("Введите название отдела (на русском языке):", function(Answer $answer){
                                                $this->user['department'] = $answer->getText();
                                                $this->ask("Введите название должности (на русском языке):", function(Answer $answer){
                                                    $this->user['position'] = $answer->getText();
                                                    $this->say("*ПРОВЕРКА ДАННЫХ:*\n".
                                                    "Местоположение: ".$this->user['location']."\n".
                                                    "Фамилия (русс.): ".$this->user['last_name_rus']."\n".
                                                    "Имя (русс.): ".$this->user['first_name_rus']."\n".
                                                    "Фамилия (англ.): ".$this->user['last_name_eng']."\n".
                                                    "Имя (англ.): ".$this->user['first_name_eng']."\n".
                                                    "Отдел: ".$this->user['department']."\n".
                                                    "Должность: ".$this->user['position']."\n"
                                                    ,["parse_mode"=>"markdown"]);
                                                    $this->ask(Question::create("Если данные верны, нажмите кнопку Подтвердить, или отмена если данные не верны")
                                                        ->addButtons([
                                                            Button::create("Подтвердить")->value("Подтвердить"),
                                                            Button::create("Отмена")->value("Отмен")
                                                    ]), function(Answer $answer){
                                                        if($answer->getText() == "Подтвердить"){
                                                            $this->say("Вы подтвердили создание учетной записи");
                                                            request_new_user::create([
                                                                'adLocation' => $this->user['location'],
                                                                'last_name_rus' => $this->user['last_name_rus'],
                                                                'first_name_rus' => $this->user['first_name_rus'],
                                                                'last_name_eng' => $this->user['last_name_eng'],
                                                                'first_name_eng' => $this->user['first_name_eng'],
                                                                'department' => $this->user['department'],
                                                                'position' => $this->user['position'],
                                                                'status' => 'request'
                                                                ]);
                                                        }
                                                        elseif($answer->getText() == "Отменить"){
                                                            $this->say("Вы отменили создание учетной записи");

                                                        }
                                                        else {
                                                            $this->say("Вы ввели неправильную команду. Повторите все заново.");
                                                        }
                                                    });
                                        });
                                    });
                                });
                            });
                        });
                    });
                });
            }
            else {
                $this->say("Вы отменили создание пользователя.");
            }
        });
    }
}
