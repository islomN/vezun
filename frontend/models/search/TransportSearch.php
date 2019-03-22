<?php
namespace frontend\models\search;

use common\models\map\Map;
use common\models\searchmodels\TransportInfo;

class TransportSearch extends DefaultSearch
{

    public $from_country_id;
    public $from_region_id;

    public $to_country_id;
    public $to_region_id;


    public function search(){
        $this->model = TransportInfo::find();

        $from_map = $this->searchMap("from");
        if($from_map){
            $this->model->andWhere(['in', 'from_map_id', $from_map]);
        }

        $to_map = $this->searchMap("to");
        if($to_map){
            $this->model->andWhere(['in', 'to_map_id', $to_map]);
        }

        return $this->model;
    }

}