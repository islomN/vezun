<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CargoInfo */

$this->title = 'Create Cargo Info';
$this->params['breadcrumbs'][] = ['label' => 'Cargo Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cargo-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
