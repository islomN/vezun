<?php
namespace frontend\controllers;

use common\components\telegram\Telegram;
use yii\web\Controller;

class Telegram2Controller extends Controller {

    public function init(){
        $BOT_TOKEN =  '715073307:AAHKgz0tGD8boBuWbGyV8d26yBYiTjE7uNs';
        $API_URL ='https://api.telegram.org/bot'.$BOT_TOKEN.'/';

        $sendto =$API_URL."sendmessage?chat_id=".'749493022'."&text=123";
        file_get_contents($sendto);

        file_put_contents(\Yii::getAlias('@frontend/web/').'logging',date("Y-m-d H:i:s"));
        $telegram = Telegram::Instance();
//        $res = $this->telegram->setWebhook(['url' => "https://w-eb.uz/telegram"]);

        // print_r($this->telegram->hook());
        print_r($telegram->sendMessage(['chat_id' => '749493022', 'text' => 'asd']));
//
//        $telegram = new TelegramHook();
//        $telegram->init();
    }
}