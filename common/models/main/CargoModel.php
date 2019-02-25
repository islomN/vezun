<?php
namespace common\models\main;

use common\models\Cargo;
use common\models\CargoInfo;
use common\models\CargoUserInfo;
use common\models\Date;
use common\models\map\City;
use common\models\map\Country;
use common\models\map\Map;
use common\models\map\Region;
use common\models\searchmodels\BotUser;
use common\models\searchmodels\TransportType;


class CargoModel extends BaseCargoModel
{
    const SCENARIO_BOT = "bot";
    const SCENARIO_SITE = "site";

    /* cargo info*/
    public $title;
    public $weight;
    public $volume;
    /* cargo info*/

    public function __construct($id = null)
    {
        if($id){
            $this->model = CargoInfo::findOne($id);
        }else{
            $this->model = new CargoInfo;
        }

        return parent::__construct();
    }

    public function rules()
    {
        return [
            [['from', 'to','name', 'phone', 'transport_type_id','title','weight', 'volume'], 'required'],
            [['from_city_id'], 'exist', 'targetClass' => City::className(), 'targetAttribute' => ['from_city_id' => 'id']],
            [['to_city_id'], 'exist', 'targetClass' => City::className(), 'targetAttribute' => ['to_city_id' => 'id']],
            [['from_country_id'], 'exist', 'targetClass' => Country::className(), 'targetAttribute' => ['from_country_id' => 'id']],
            [['to_country_id'], 'exist', 'targetClass' => Country::className(), 'targetAttribute' => ['to_country_id' => 'id']],
            [['from_region_id'], 'exist', 'targetClass' => Region::className(), 'targetAttribute' => ['from_region_id' => 'id']],
            [['to_region_id'], 'exist', 'targetClass' => Region::className(), 'targetAttribute' => ['to_region_id' => 'id']],

            [['from', 'to'], 'dateValidate'],

            [['name', 'phone','title', ], 'string', 'max' => 255],
            [['bot_user_id'], 'exist', 'targetClass' => BotUser::className(), 'targetAttribute' => ['bot_user_id' => 'id']],

            [['weight', 'volume'], 'number'],
            [['transport_type_id'], 'exist', 'targetClass' => TransportType::className(), 'targetAttribute' => ['transport_type_id' => 'id'] ]
        ];
    }

    public function innerSave($validate = true){


        $list_foreign_item = self::listForeignItems();

        foreach($list_foreign_item as $key => $foreign_item){
            $this->setForeignItem($foreign_item);
        }
    }


    public static function find($id){
        $self = new self;
        if($id){
            $self->model = CargoInfo::findOne($id);
        }else{
            $self->model = new CargoInfo();
        }
        return $self;
    }


    public static function listForeignItems(){
        return [
            'dates' => ['class' => Date::class, 'key' => 'date_id'],
            'cargo_info' => ['class' => Cargo::class, 'key' => 'cargo_id'],
        ];
    }

}