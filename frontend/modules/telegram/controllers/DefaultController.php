<?php

namespace frontend\modules\telegram\controllers;

use common\components\telegram\Telegram;
use frontend\components\TelegramHook;
use Longman\TelegramBot\Request;
use yii\web\Controller;

/**
 * Default controller for the `telegram` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */


    public function actionSet()
    {
        //define('BOT_TOKEN', '715073307:AAHKgz0tGD8boBuWbGyV8d26yBYiTjE7uNs');
//define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
        $bot_api_key  = '715073307:AAHKgz0tGD8boBuWbGyV8d26yBYiTjE7uNs';
        $bot_username = 'vezunbot';
        $hook_url     = 'https://2fd72f5f.ngrok.io/telegram.php';

        $telegram = new \Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
        $result = $telegram->setWebhook($hook_url);
        if ($result->isOk()) {
            echo $result->getDescription();
        }

        return;
    }

    public function actionIndex(){
        // Load composer

        $bot_api_key  = '715073307:AAHKgz0tGD8boBuWbGyV8d26yBYiTjE7uNs';
        $bot_username = 'vezunbot';

        try {
            // Create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

            // Handle telegram webhook request
//            print_r($telegram->handle());
            Request::sendMessage(['chat_id' => '749493022', 'text' =>'123']);
        } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
            print_r($e->getMessage());
            // Silence is golden!
            // log telegram errors
            // echo $e->getMessage();
        }
    }

}
