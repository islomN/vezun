<?php
namespace frontend\components;

use common\components\telegram\Telegram;
class TelegramHook{

    public $telegram;
    public function init(){
        file_put_contents(\Yii::getAlias('@frontend/web/').'logging', "asdad");
        $this->telegram = Telegram::Instance();
        $res = $this->telegram->setWebhook(['url' => \Yii::getAlias("@server")]);

        print_r($this->telegram->hook());
        print_r($this->telegram->getChat(['chat_id' => '749493022']));
    }

    public function sendMessage(){

    }



}