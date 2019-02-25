
<hr>
<h3>Погрузка</h3>
<?php $from_map = $model->model->getFromMapOrNew();?>
<?= $form->field($model, 'from_country_id', ['options' => ['class' => 'col-md-4']])
    ->dropDownList($from_map->getCountryList(),
        [
            'class' => 'from-country form-control',
            'options' => [
                $from_map->country_id => ['selected' => true],
            ]
        ]
    ) ?>
<?= $form->field($model, 'from_region_id', ['options' => ['class' => 'col-md-4']])
    ->dropDownList($from_map->getRegionList(),
        [
            'class' => 'from-region inner-from-country form-control',
            'options' => [
                $from_map->region_id => ['selected' => true],
            ]
        ]
    ) ?>
<?= $form->field($model, 'from_city_id', ['options' => ['class' => 'col-md-4']])
    ->dropDownList($from_map->getCityList(),
        [
            'class' => 'from-city inner-from-region inner-from-country form-control',
            'options' => [
                $from_map->city_id => ['selected' => true],
            ]
        ]
        ) ?>
<hr>

<hr>
<h3>Выгрузка</h3>
<?php $to_map = $model->model->getToMapOrNew();?>
<?= $form->field($model, 'to_country_id', ['options' => ['class' => 'col-md-4']])
    ->dropDownList($to_map->getCountryList(),
        [
            'class' => 'to-country form-control',
            'options' => [
                $to_map->country_id => ['selected' => true],
            ]
        ]
        ) ?>
<?= $form->field($model, 'to_region_id', ['options' => ['class' => 'col-md-4']])
    ->dropDownList($to_map->getRegionList(),
        [
            'class' => 'to-region inner-from-country form-control',
            'options' => [
                $to_map->region_id => ['selected' => true],
            ]
        ]
        ) ?>
<?= $form->field($model, 'to_city_id', ['options' => ['class' => 'col-md-4']])
    ->dropDownList($to_map->getCityList(),
        [
            'class' => 'to-city inner-to-region inner-to-country form-control',
            'options' => [
                $to_map->city_id => ['selected' => true],
            ]
        ]
        ) ?>
<hr>

<?php $dates = $model->model->date;?>
<?php $model->from = $dates ? $dates->from : null;?>
<?= $form->field($model, 'from', ['options' => ['class' => 'col-md-6']])
    ->widget(\dosamigos\datepicker\DatePicker::className(), [
        'value' => $dates ? $dates->from : null,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ],
    ]) ?>
<?php $model->to = $dates ? $dates->to : null;?>
<?= $form->field($model, 'to', ['options' => ['class' => 'col-md-6']])
    ->widget(\dosamigos\datepicker\DatePicker::className(), [

        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ],
    ]) ?>

<?= $form->field($model, 'transport_type_id')
    ->dropDownList(
        \common\models\TransportType::find()->select('name, id')->orderBy('position desc')->indexBy('id')->column(),
        [
            'options' => [
                $model->model->transport_type_id => ['selected' => true]
            ]
        ]
    ) ?>


<hr>
<h3>Информация о пользователе</h3>

<?php $user = $model->model->userInfo;?>
<?= $form->field($model, 'name')->textInput(['value' => $user ? $user->name : null]) ?>
<?= $form->field($model, 'phone')->textInput(['value' => $user ? $user->phone : null]) ?>


<?php $this->registerJsFile('/js/map.js', ['depends' => \backend\assets\AppAsset::class])?>