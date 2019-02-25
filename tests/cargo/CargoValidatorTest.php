<?php

class CargoValidatorTest extends \PHPUnit\Framework\TestCase
{

    public function testDateWrong(){
        $model = new \common\models\main\CargoModel;
        $model->from = "12-12-19";
        $model->to = "12-12-18";
        $this->assertIsTrue($model->validate('from'));
    }
}