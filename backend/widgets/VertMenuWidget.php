<?php
namespace backend\widgets;


use yii\base\Widget;
use yii\helpers\Url;

class VertMenuWidget extends Widget {

    public function run(){
        $menus = [
            [
                'text' => 'Транспорты',
                'url' => Url::to('/transport-info'),
                'controller' => 'transport-info',
            ],
            [
                'text' => 'Грузы',
                'url' => Url::to('/cargo-info'),
                'controller' => 'cargo-info',
            ],
            [
                'text' => 'Пользователи Бота',
                'url' => Url::to('/bot-user'),
                'controller' => 'bot-user',
            ],
            [
                'text' => 'Тип транспортов',
                'url' => Url::to('/transport-type'),
                'controller' => 'transport-type',
            ],
            [
                'text' => 'Телеграм',
                'url' => Url::to('/telegram-info'),
                'controller' => 'telegram-info',
            ],
            [
                'text' => 'Страны',
                'url' => Url::to('/country'),
                'controller' => 'country',
            ],
            [
                'text' => 'Регионы',
                'url' => Url::to('/region'),
                'controller' => 'region',
            ],


        ];
        return $this->render('vert-menu', compact('menus'));
    }
}