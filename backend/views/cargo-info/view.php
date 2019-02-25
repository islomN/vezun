<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CargoInfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cargo Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cargo-info-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'date_id',
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
            [
                'attribute' => 'transport_type_id',
                'value' => function($model){
                    $transport_type = $model->transportType;
                    if(!$transport_type){
                        return;
                    }

                    return $transport_type->name;
                }
            ],
            [
                'attribute' => 'cargo_id',
                'value' => function($model){
                    $cargo = $model->cargo;

                    if(!$cargo){
                        return;
                    }

                    return "Название: ".$cargo->title."<br>"
                    ." вес ".$cargo->weight . " т<br>"
                    ." объем ".$cargo->volume . " м3<br>";
                },
                'format' => 'html'
            ],
            //'user_info_id',
            [
                'attribute' => 'user_info_id',
                'value' => function($model){
                    $user = $model->userInfo;
                    if(!$user){
                        return;
                    }
                    $bot_user = $user->botUser;

                    if($bot_user){
                        $str ="Имя: <a href='".\yii\helpers\Url::to(['/bot-user/view', 'id' => $bot_user->id])."'>".$user->name."</a><br>";
                    }else{
                        $str = "Имя: ".Html::encode($user->name)."<br>";
                    }

                    return $str . "телефон: ".$user->phone;
                },
                'format' => 'html'
            ],
            'created_at',
        ],
    ]) ?>

</div>
