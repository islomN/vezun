<?php
namespace frontend\models\search;

use common\models\Cargo;
use common\models\map\Map;
use common\models\CargoInfo;

class CargoSearch extends DefaultSearch
{


    public $transport_type;


    public function search(){
        $this->model = CargoInfo::find();

        $cargo = $this->searchCargo();
        if($cargo){
            $this->model->andWhere(['in', 'cargo_id', $cargo]);
        }

        if($this->transport_type){
            $this->model->andWhere(['transport_type_id' => $this->transport_type]);
        }

        return $this->model;
    }

}