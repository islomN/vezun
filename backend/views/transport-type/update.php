<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TransportType */

$this->title = 'Update Transport Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Transport Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transport-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
