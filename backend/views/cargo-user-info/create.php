<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CargoUserInfo */

$this->title = 'Create Cargo User Info';
$this->params['breadcrumbs'][] = ['label' => 'Cargo User Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cargo-user-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
