<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TransportInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transport-info-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->render('/partials/default-form-items', compact('form', 'model'))?>

    <hr>
    <?php $transportCargoInfo = $model->model->transportCargoInfo;?>

    <?php $weight = $transportCargoInfo ? $transportCargoInfo->weightInterval: null?>
    <?= $form->field($model, 'weight_from', ['options' => ['class' => 'col-md-6']])->textInput(['value' => $weight ? $weight->from : null])?>
    <?= $form->field($model, 'weight_to', ['options' => ['class' => 'col-md-6']])->textInput(['value' => $weight ? $weight->to : null])?>

    <?php $volume = $transportCargoInfo ? $transportCargoInfo->volumeInterval: null?>
    <?= $form->field($model, 'volume_from', ['options' => ['class' => 'col-md-6']])->textInput(['value' => $volume ? $volume->from : null])?>
    <?= $form->field($model, 'volume_to', ['options' => ['class' => 'col-md-6']])->textInput(['value' => $volume ? $volume->to : null])?>
    <hr>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
