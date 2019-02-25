<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchmodels\TransportInfo */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transport Infos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-info-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Transport Info', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Дата',
                'value' => function($model){
                    $date = $model->date;

                    if(!$date){
                        return;
                    }

                    return "Дата погрузки с <br>".$date->from ."<br> по <br>".$date->to;
                },
                'format' => 'html'
            ],
            [
                'label' => 'От куда - до куда',
                'value' => function($model){
                    $str = "";

                    $fromMap = $model->fromMap;
                    if($fromMap){
                        $str = "Место погрузки: ".$fromMap->getFullInfo();
                    }

                    $toMap = $model->toMap;
                    if($toMap){
                        $str .= "<br>". "Место выгрузки: ".$toMap->getFullInfo();
                    }

                    return $str;
                },
                'format' => 'html'
            ],
            //'user_info_id',
            'created_at',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
