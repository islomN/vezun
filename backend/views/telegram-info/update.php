<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TelegramInfo */

$this->title = 'Update Telegram Info: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Telegram Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->token]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="telegram-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
