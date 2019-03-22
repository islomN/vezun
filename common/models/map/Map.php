<?php

namespace common\models\map;

use common\models\CargoInfo;
use common\models\TransportInfo;
use Yii;

/**
 * This is the model class for table "map".
 *
 * @property int $id
 * @property int $country_id
 * @property int $region_id
 * @property int $city_id
 *
 * @property CargoInfo[] $cargoInfos
 * @property Cities $city
 * @property Countries $country
 * @property Regions $region
 * @property TransportInfo[] $transportInfos
 */
class Map extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'region_id', 'city_id'], 'integer'],
//            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
//            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
//            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_id' => 'Country ID',
            'region_id' => 'Region ID',
            'city_id' => 'City ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargoInfos()
    {
        return $this->hasMany(CargoInfo::className(), ['map_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportInfos()
    {
        return $this->hasMany(TransportInfo::className(), ['map_id' => 'id']);
    }

    public function getCountryList(){
        return Country::find()->select('name, id')->indexBy('id')->orderBy('name')->column();
    }

    public function getRegionList(){
        if(!$this->country_id){
            return [];
        }
        return Region::find()->select('name, id')->andWhere(['country_id' => $this->country_id])->indexBy('id')->orderBy('name')->column();
    }

    public function getCityList(){
        if(!$this->region_id){
            return [];
        }
        return City::find()->select('name, id')->andWhere(['region_id' => $this->region_id])->indexBy('id')->orderBy('name')->column();
    }

    public function getFullInfo(){
        $info = [];
        if($country = $this->country){
            $info[] = $country->name;
        }

        if($region = $this->region){
            $info[] = $region->name;
        }
//
//        if($city = $this->city){
//            $info[] = $city->name;
//        }

        return implode(", ", $info);
    }

    public function getCountryName(){
        if($country = $this->country){
            return $country->name;
        }

        return null;
    }

    public function getRegionName(){
        if($region = $this->region){
            return $region->name;
        }

        return null;
    }

    public static function getInfo($country_id, $region_id = null){
        $country = Country::findOne($country_id);

        if($region_id){
            $region = Region::findOne($region_id);
            $country->name .= ", ".$region->name;
        }

        return $country->name;
    }
}
