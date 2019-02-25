<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TransportInfo */

$this->title = 'Create Transport Info';
$this->params['breadcrumbs'][] = ['label' => 'Transport Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
