<?php
namespace common\components\telegram;

use common\models\searchmodels\TelegramInfo;

class Telegram extends BaseTelegram {

    private $chat_id;
    private static $instance;


    public static function Instance(){

        if(self::$instance == null){
            $telegram_info = TelegramInfo::find()->select('token')->limit(1)->one();
            if(!$telegram_info){
                throw new \Exception("telegram info empty");
            }
            self::$instance = new self;
            self::$instance->botToken = $telegram_info->token;
        }

        return self::$instance;
    }
}
