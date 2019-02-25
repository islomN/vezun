<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TransportInfo */

$this->title = 'Update Transport Info: ' . $model->model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transport Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' =>  $model->model->id, 'url' => ['view', 'id' => $model->model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transport-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
