<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchmodels\TransportType */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transport Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Transport Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name_ru',
            'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
