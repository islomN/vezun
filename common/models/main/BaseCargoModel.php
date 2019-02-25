<?php
namespace common\models\main;

use common\models\BotUserInfo;
use common\models\CargoUserInfo;
use common\models\map\Map;
use yii\base\Model;

class BaseCargoModel extends Model{

    public $model;
    /* map info */

    public $to_country_id;
    public $to_region_id;
    public $to_city_id;
    public $from_country_id;
    public $from_region_id;
    public $from_city_id;

    /*  map info */

    /* date info*/
    public $from;
    public $to;
    /* date info*/

    /* user info*/
    public $name;
    public $phone;
    public $bot_user_id;
    /* user info*/

    /* cargo info*/
    public $transport_type_id;
    /* cargo info*/


    public function rules(){

    }

    public function save($validate = true){
        if($validate){
            if(!$this->validate()){
                return false;
            }
        }

        $transaction = \Yii::$app->db->beginTransaction();
        $this->setUserInfo();

        $this->model->setAttributes($this->attributes);

        $this->innerSave();
        $this->setMap();


        if($this->model->save()){
            $transaction->commit();
            return true;
        }else{
            $transaction->rollBack();
            return false;
        }
    }

    public function innerSave(){


    }

    public function dateValidate(){

        $reg_date =  "/\d{4}-\d{1,2}-\d{1,2}/";
        if(!preg_match($reg_date, $this->from)){
            $this->addError('from', "Введите дату");
            return;
        }

        if(!preg_match($reg_date, $this->to)){
            $this->addError('to', "Введите дату");
            return;
        }



        $from = strtotime($this->from);
        $to = strtotime($this->to);

        if($from < strtotime(date("Y-m-d"))){
            $this->addError('from', "Введите мин сегод дату");
        }

        if($from > $to){
            $this->addError('from', "Знечение не должно быть больше");
            $this->addError('to', "Знечение не должно быть меньше");
        }
    }



    public function getForeignItem($class, $key){
        if(!$this->model->isNewRecord){
            $item = $class::findOne($this->model->$key);
        }else{
            $item = new $class;
        }

        return $item;
    }


    public function setForeignItem($foreign_item){
        $item = $this->getForeignItem($foreign_item['class'], $foreign_item['key']);

        $item->setAttributes($this->attributes);
        $item->save(false);

        $this->model->{$foreign_item['key']} = $item->id;
    }



    public function setMap(){
        $to_map = $this->model->toMap;
        $from_map = $this->model->fromMap;

        if(!$to_map){
            $to_map =  new Map();
        }

        if(!$from_map){
            $from_map =  new Map();
        }

        $to_map->country_id = $this->to_country_id;
        $to_map->region_id = $this->to_region_id;
        $to_map->city_id = $this->to_city_id;
        $to_map->save(false);


        $from_map->country_id = $this->from_country_id;
        $from_map->region_id = $this->from_region_id;
        $from_map->city_id = $this->from_city_id;

        $from_map->save(false);

        $this->model->from_map_id = $from_map->id;
        $this->model->to_map_id = $to_map->id;
    }

    public function setUserInfo(){
        if($this->model->isNewRecord){
            $user = new CargoUserInfo();
        }else{
            $user = $this->model->userInfo;
        }

        $user->setAttributes($this->attributes);
        $user->save(false);
        $this->model->user_info_id = $user->id;

        if($this->bot_user_id && $this->model->isNewRecord){
            $bot_user_info = new BotUserInfo;
            $bot_user_info->user_info_id = $user->id;
            $bot_user_info->bot_user_id = $this->bot_user_id;
            $bot_user_info->save(false);
        }

    }

    public static function listForeignItems(){

    }

}