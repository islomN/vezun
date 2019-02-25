<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CargoUserInfo */

$this->title = 'Update Cargo User Info: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Cargo User Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cargo-user-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
