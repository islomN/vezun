<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TransportInfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transport Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transport-info-view">

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
                'attribute' => 'transport_cargo_info_id',
                'value' => function($model){
                    $transport_cargo_info = $model->transportCargoInfo;
                    if(!$transport_cargo_info){
                        return;
                    }

                    $str = "";
                    if($weight = $transport_cargo_info->weightInterval){
                        $str = "Грузоподъем <br>";
                        $str .= "c <b>".$weight->from." т</b> ";
                        $str .= "до <b>".$weight->to." т</b>";
                    }
                    $str .= "<br><br>";
                    if($volume = $transport_cargo_info->volumeInterval){
                        $str .= "Объем <br>";
                        $str .= "c <b>".$volume->from." м3</b> ";
                        $str .= "до <b>".$volume->to." м3</b>";
                    }


                    return $str;
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'user_info_id',
                'value' => function($model){
                    $user = $model->userInfo;
                    if(!$user){
                        return;
                    }
                    $bot_user = $user->botUser;

                    if($bot_user){
                        $str ="Имя: <a href='".\yii\helpers\Url::to(['/bot-user/view', 'id' => $bot_user->bot_user_id])."'>".$user->name."</a><br>";
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
