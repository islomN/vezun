<?php
namespace frontend\components\telegram;

use common\components\telegram\Telegram;
use common\components\telegram\TelegramCache;
use common\models\Letter;

class TelegramHook{

    public $input;

    public $telegram;
    public $chat_id;

    public $last_message_id;
    public $text;

    public $firstname;
    public $lastname;
    public $fullname;

    public $username;

    public $page;
    public $action;

    public $letters;

    public $wordlang;
    public $lang;

    public $limit =5;

    public function init(){

        $this->telegram = Telegram::Instance();
        $this->input = $this->telegram->hook();
        $this->letters = Letter::getCacheItems();

        if(!isset($_GET['msg'])){
            TelegramChatAction::init($this)->setChatInfo();

        }else{
            $this->text = $_GET['msg'];
        }

        $this->action();
    }

    public function action(){
        TelegramAction::init($this);
    }

    public function getLetter($key){
        return $this->letters[$key]["word_".$this->lang];
    }

}