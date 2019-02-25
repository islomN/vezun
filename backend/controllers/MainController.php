<?php
namespace backend\controllers;

use common\models\main\CargoModel;
use yii\web\Controller;

class MainController extends BackendController {

    public function actionIndex(){

        return $this->render('index');
    }
}