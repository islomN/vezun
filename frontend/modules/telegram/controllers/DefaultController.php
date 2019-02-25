<?php

namespace frontend\modules\telegram\controllers;

use frontend\components\TelegramHook;
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


    public function actionIndex()
    {

        $telegram = new TelegramHook();
        $telegram->init();
    }

}
