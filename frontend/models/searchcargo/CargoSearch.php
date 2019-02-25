<?php
namespace frontend\models\search;

use common\models\Cargo;
use common\models\map\Map;
use common\models\CargoInfo;

class CargoSearch
{
    public $country_id;
    public $region_id;
    public $city_id;

    public $weight_from;
    public $weight_to;
    public $volume_from;
    public $volume_to;

    public $transport_type;

    private $model;

    public function search(){
        $this->model = CargoInfo::find();

        $map = $this->searchMap();
        if($map){
            $this->model->andWhere(['in', 'map_id', $map]);
        }

        $cargo = $this->searchCargo();
        if($cargo){
            $this->model->andWhere(['in', 'cargo_id', $cargo]);
        }

        if($this->transport_type){
            $this->model->andWhere(['transport_type_id' => $this->transport_type]);
        }
    }

    public function searchCargo(){
        $cargo = Cargo::find()->select('id');

        if($this->weight_from){
            $cargo->andWhere(['>=', 'weight', $this->weight_from]);
        }

        if($this->weight_to){
            $cargo->andWhere(['<=', 'weight', $this->weight_to]);
        }

        if($this->volume_from){
            $cargo->andWhere(['>=', 'volume', $this->volume_from]);
        }

        if($this->volume_to){
            $cargo->andWhere(['<=', 'volume', $this->volume_to]);
        }

        return $cargo;
    }

    public function searchMap(){

        if(!$this->country_id){
            return;
        }

        $map = Map::find()->select('id')->andWhere(['country_id' => $this->country_id]);

        if($this->region_id){
            $map->andWhere(['region_id' => $this->region_id]);
        }

        if($this->city_id){
            $map->andWhere(['city_id' => $this->city_id]);
        }

        return $map;

    }

}