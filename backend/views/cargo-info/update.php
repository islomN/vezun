<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CargoInfo */

$this->title = 'Update Cargo Info: ' . $model->model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cargo Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->model->id, 'url' => ['view', 'id' => $model->model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cargo-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
