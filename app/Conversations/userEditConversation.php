<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use App\Http\Controllers\adUserController;
use App\Http\Controllers\mailController;
use App\user;

class userEditConversation extends Conversation
{
    /**
     * First question
     */
    protected $search_user;

    public function askReason()
    {
        $users = user::get();
        $user_list = "";
        foreach ($users as $user) {
            $user_list .= $user->nameRus."\n";
        }
        $this->say("*Для редактирования скопируйте ФИО из списка ниже и отправьте ответом в сообщении*",["parse_mode"=>"markdown"]);
        $question = Question::create($user_list)
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason');

        return $this->ask($question, function (Answer $answer) {
            $search_user = user::where("nameRus",$answer->getText())->first();
                if ($search_user) {
                    $this->search_user = $search_user;
                    $this->say("*РЕДАКТИРОВАНИЕ ПОЛЬЗОВАТЕЛЯ:* \nФИО: ".$search_user->nameRus."\n"."Должность: ".$search_user->position."\nМобильный: ".$search_user->mobile,["parse_mode"=>"markdown"]);
                    $this->ask(
                        Question::create("Выберите действие: ")
                            ->addButtons([
                                Button::create('Изменить должность')->value('edit_position'),
                                Button::create('Изменить мобильный')->value('edit_mobile'),
                            ]), function(Answer $answer){
                                $action = $answer->getText();
                                if($action == 'edit_position'){
                                    $this->ask("Введите новое значения для должности:", function(Answer $answer){
                                        $position_old = $this->search_user->position;
                                        $position_new = $answer->getText();
                                        $this->search_user->position = $position_new;
                                        $this->search_user->save();
                                        $who = user::where("telegram_id",$this->bot->getUser()->getId())->first();
                                        adUserController::updatePosition($this->search_user->cn,$position_new);
                                        mailController::changed_position((object)['nameRus' => $this->search_user->nameRus,'email' => $this->search_user->email,"position_old" => $position_old,"position_new" => $position_new,"who" => $who->nameRus]);
                                        $this->say("*ОБНОВЛЕННЫЕ ДАННЫЕ*\nФИО: ".$this->search_user->nameRus."\nДолжность: ".$this->search_user->position,["parse_mode"=>"markdown"]);
                                    });
                                }
                                elseif($action == 'edit_mobile'){
                                    $this->ask("Введите новое значения для номера мобильного телефона:", function(Answer $answer){
                                        $mobile_old = $this->search_user->mobile;
                                        $mobile_new = $answer->getText();
                                        $this->search_user->mobile = $mobile_new;
                                        $this->search_user->save();
                                        $who = user::where("telegram_id",$this->bot->getUser()->getId())->first()->nameRus;
                                        adUserController::updateMobile($this->search_user->cn,$mobile_new);
                                        mailController::changed_mobile((object)['nameRus' => $this->search_user->nameRus,'email' => $this->search_user->email,"mobile_old" => $mobile_old,"mobile_new" => $mobile_new, "who"=>$who]);
                                        $this->say("*ОБНОВЛЕННЫЕ ДАННЫЕ*\nФИО: ".$this->search_user->nameRus."\nМобильный: ".$this->search_user->mobile,["parse_mode"=>"markdown"]);
                                    });
                                }
                                else{
                                    $this->say("Неправильная команда");
                                }
                            });
                } else {
                    $this->say("Неправильно введены данные");
                }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }
}

