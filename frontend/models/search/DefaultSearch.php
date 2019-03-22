<?php
namespace frontend\models\search;

use common\models\map\Map;

abstract class DefaultSearch{

    public $from_country_id;
    public $from_region_id;

    public $to_country_id;
    public $to_region_id;


    public $weight_from;
    public $weight_to;
    public $volume_from;
    public $volume_to;

    protected $model;

    abstract public function search();

    protected function searchCargo(){
//        if($this->weight_)
    }

    protected function searchMap($prefix){

        if(!$this->{$prefix."_country_id"}){
            return;
        }

        $map = Map::find()->select('id')->andWhere(['country_id' => $this->{$prefix."_country_id"}]);

        if($this->{$prefix."_region_id"}){
            $map->andWhere(['region_id' => $this->{$prefix."_region_id"}]);
        }


        return $map;

    }
}