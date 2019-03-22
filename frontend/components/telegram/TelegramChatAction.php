<?php
namespace frontend\components\telegram;

class TelegramChatAction{

    private $telegram;
    private $chat;

    public static function init(TelegramHook $telegram){
        $self = new self;
        $self->telegram = $telegram;

        return $self;
    }

    public function setChatInfo(){

        if(!isset($this->telegram->input->message->chat)){
            return false;
        }

        $this->chat = $this->telegram->input->message->chat;

        $this->setChatId();
        $this->setChatUsername();
        $this->setFullname();
        $this->setMessage();

        return true;
    }

    private function setFullname(){
        $this->telegram->fullname .= $this->chat->first_name;
    }

    private function setMessage(){
        $this->telegram->text .= $this->telegram->input->message->text;
    }

    private function setChatUsername(){

        $this->telegram->username = $this->chat->username;
    }

    private function setChatId(){

        $this->telegram->chat_id =$this->chat->id;

    }
}