<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchmodels\BotUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bot Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Bot User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= Html::a('Send Message', ['messages/create'], [
        'class' => 'btn btn-danger',

    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'chat_id',
            'name',
            'phone',
            'nickname',
            //'created_at',
            //'updated_at',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
