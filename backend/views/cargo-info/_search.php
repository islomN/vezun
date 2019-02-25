<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\searchmodels\CargoInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cargo-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'map_id') ?>

    <?= $form->field($model, 'date_id') ?>

    <?= $form->field($model, 'transport_type_id') ?>

    <?= $form->field($model, 'cargo_id') ?>

    <?php // echo $form->field($model, 'user_info_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
