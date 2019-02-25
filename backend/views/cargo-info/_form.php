<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CargoInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cargo-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $this->render('/partials/default-form-items', compact('form', 'model'))?>

    <?php $cargo = $model->model->cargo;?>
    <?= $form->field($model, "title", ['options' => ['class' => 'col-md-4']])->textInput(['value' => $cargo ? $cargo->title: null ]);?>
    <?= $form->field($model, "weight", ['options' => ['class' => 'col-md-4']])->textInput(['value' => $cargo ? $cargo->weight: null ]);?>
    <?= $form->field($model, "volume", ['options' => ['class' => 'col-md-4']])->textInput(['value' => $cargo ? $cargo->volume: null ]);?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
