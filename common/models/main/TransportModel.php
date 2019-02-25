<?php
namespace common\models\main;

use common\models\Date;
use common\models\main\cargointerfaces\CargoInterface;
use common\models\map\City;
use common\models\map\Country;
use common\models\map\Map;
use common\models\map\Region;
use common\models\searchmodels\BotUser;
use common\models\searchmodels\CargoUserInfo;
use common\models\searchmodels\TransportType;
use common\models\TransportCargoInfo;
use common\models\TransportInfo;
use common\models\VolumeInterval;
use common\models\WeightInterval;
use yii\web\NotAcceptableHttpException;

class TransportModel extends BaseCargoModel
{

    /* transport cargo info*/
    public $weight_from;
    public $weight_to;

    public $volume_from;
    public $volume_to;
    /* transport cargo info*/

    /**/
    public $transport_cargo_info_id;
    /* */
    public function __construct($id = null)
    {
        if($id){
            $this->model = TransportInfo::findOne($id);
        }else{
            $this->model = new TransportInfo;
        }

        return parent::__construct();
    }

    public function rules(){
        return [
            [['from', 'to','weight_from', 'weight_to','volume_from', 'volume_to', 'name', 'phone', 'transport_type_id'], 'required', 'message' => 'Введите данные'],
            [['from_city_id'], 'exist', 'targetClass' => City::className(), 'targetAttribute' => ['from_city_id' => 'id']],
            [['to_city_id'], 'exist', 'targetClass' => City::className(), 'targetAttribute' => ['to_city_id' => 'id']],
            [['from_country_id'], 'exist', 'targetClass' => Country::className(), 'targetAttribute' => ['from_country_id' => 'id']],
            [['to_country_id'], 'exist', 'targetClass' => Country::className(), 'targetAttribute' => ['to_country_id' => 'id']],
            [['from_region_id'], 'exist', 'targetClass' => Region::className(), 'targetAttribute' => ['from_region_id' => 'id']],
            [['to_region_id'], 'exist', 'targetClass' => Region::className(), 'targetAttribute' => ['to_region_id' => 'id']],

            [['from', 'to'], 'dateValidate'],

            [['name', 'phone',], 'string', 'max' => 255],
            [['bot_user_id'], 'exist', 'targetClass' => BotUser::className(), 'targetAttribute' => ['bot_user_id' => 'id']],

            [['weight_from', 'weight_to','volume_from', 'volume_to',], 'number', 'message' => 'Введите число'],
            [['weight_from', 'weight_to'], 'weightIntervalValidate'],
            [['volume_from', 'volume_to',], 'volumeIntervalValidate'],

            [['transport_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransportType::className(), 'targetAttribute' => ['transport_type_id' => 'id'] ]
        ];
    }

    public function innerSave($validate = true){



        $list_foreign_item = self::listForeignItems();

        foreach($list_foreign_item as $key => $foreign_item){
            $this->setForeignItem($foreign_item);
        }

        $this->setForeignIntervalItems();
    }

    public function setForeignIntervalItems(){
        $item = TransportCargoInfo::findOne($this->model->transport_cargo_info_id);
        if(!$item){
            $item = new TransportCargoInfo();
        }

        $item->weight_interval_id = $this->setForeignWeightInterval($item);
        $item->volume_interval_id = $this->setForeignVolumeInterval($item);

        $item->save(false);
        $this->model->transport_cargo_info_id = $item->id;

        return true;
    }

    public function setForeignWeightInterval($transport_cargo_model)
    {
        $item = WeightInterval::findOne($transport_cargo_model->weight_interval_id);
        if(!$item){

            $item = new WeightInterval();
        }

        $item->from = $this->weight_from;
        $item->to = $this->weight_to;


        $item->save(false);
        return $item->id;
    }

    public function setForeignVolumeInterval($transport_cargo_model)
    {
        $item = VolumeInterval::findOne($transport_cargo_model->volume_interval_id);
        if(!$item){

            $item = new VolumeInterval();
        }

        if(!$item){
            throw new NotAcceptableHttpException("");
        }

        $item->from = $this->volume_from;
        $item->to = $this->volume_to;

        $item->save(false);
        return $item->id;
    }

    public static function find($id){
        $self = new self;
        if($id){
            $self->model = TransportCargoInfo::findOne($id);
        }else{
            $self->model = new TransportCargoInfo();
        }
        return $self;
    }


    public static function listForeignItems(){
        return [
            'dates' => ['class' => Date::class,'name' => 'date', 'key' => 'date_id'],
        ];
    }

    public function weightIntervalValidate($attribute){

        if($this->weight_from > $this->weight_to){
            $this->addError("weight_from", "");
            $this->addError("weight_to", "");
        }
    }

    public function volumeIntervalValidate($attribute){

        if($this->volume_from > $this->volume_to){
            $this->addError("volume_from", "");
            $this->addError("volume_to", "");
        }
    }
}