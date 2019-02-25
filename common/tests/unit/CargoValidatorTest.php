<?php namespace common\tests;

use common\models\Cargo;
use common\models\main\CargoModel;

class CargoValidatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testFromWrong()
    {
        $model = new CargoModel();

        $model->from = date("Y-m-d");
        $model->to = date("Y-m-d", time()-3600*24);
        $this->assertFalse($model->validate("from"), $model->getFirstError('from'));

    }

    public function testFromTrue()
    {
        $model = new CargoModel();

        $model->from = date("Y-m-d");
        $model->to = date("Y-m-d", time()+3600*24);
        $this->assertTrue($model->validate("from"), $model->getFirstError('from'));

    }

    public function testFromWrongDate()
    {
        $model = new CargoModel();

        $model->from = "asdasd";
        $model->to = "asdas";
        $this->assertFalse($model->validate("from"), $model->getFirstError('from'));

    }

    public function testToWrong()
    {
        $model = new CargoModel();

        $model->from = date("Y-m-d");
        $model->to = date("Y-m-d", time()-3600*24);
        $this->assertFalse($model->validate("to"), $model->getFirstError('to'));

    }

    public function testToTrue()
    {
        $model = new CargoModel();

        $model->from = date("Y-m-d");
        $model->to = date("Y-m-d", time()+3600*24);
        $this->assertTrue($model->validate("to"), $model->getFirstError('to'));

    }

    public function testToWrongDate()
    {
        $model = new CargoModel();

        $model->from = date("Y-m-d");
        $model->to = "asdas";
        $this->assertFalse($model->validate("to"), $model->getFirstError('to'));

    }

    public function testCountryWrong(){
        $model = new CargoModel();
        $model->country_id  = 1111;
        $this->assertFalse($model->validate("country_id"), $model->getFirstError('country_id'));
    }

    public function testCountryTrue(){
        $model = new CargoModel();
        $model->country_id  = 108;
        $this->assertTrue($model->validate("country_id"), $model->getFirstError('country_id'));
    }

    public function testRegionWrong(){
        $model = new CargoModel();
        $model->country_id  = 108;
        $model->region_id  = 20;
        $this->assertFalse($model->validate("region_id"), $model->getFirstError('region_id'));
    }
}