<?php

namespace common\models;

use common\models\map\Map;
use Yii;

/**
 * This is the model class for table "transport_info".
 *
 * @property int $id
 * @property int $map_id
 * @property int $date_id
 * @property int $transport_type_id
 * @property int $transport_cargo_info_id
 * @property int $user_info_id
 * @property string $created_at
 * @property int $status
 *
 * @property TransportCargo[] $transportCargos
 * @property TransportCargoInfo $transportCargoInfo
 * @property Date $date
 * @property Map $map
 * @property TransportType $transportType
 * @property CargoUserInfo $userInfo
 */
class TransportInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transport_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['to_map_id', 'from_map_id', 'date_id', 'transport_type_id', 'transport_cargo_info_id', 'user_info_id', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['transport_cargo_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransportCargoInfo::className(), 'targetAttribute' => ['transport_cargo_info_id' => 'id']],
            [['date_id'], 'exist', 'skipOnError' => true, 'targetClass' => Date::className(), 'targetAttribute' => ['date_id' => 'id']],
            [['to_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => Map::className(), 'targetAttribute' => ['from_map_id' => 'id']],
            [['from_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => Map::className(), 'targetAttribute' => ['to_map_id' => 'id']],
            [['transport_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransportType::className(), 'targetAttribute' => ['transport_type_id' => 'id']],
            [['user_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => CargoUserInfo::className(), 'targetAttribute' => ['user_info_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'to_map_id' => 'Map ID',
            'from_map_id' => 'Map ID',
            'date_id' => 'Date ID',
            'transport_type_id' => 'Transport Type ID',
            'transport_cargo_info_id' => 'Transport Cargo Info ID',
            'user_info_id' => 'User Info ID',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }

    public function beforeDelete()
    {
        $to_map = $this->toMap;
        if($to_map){
            $to_map->delete();
        }

        $from_map = $this->fromMap;
        if($from_map){
            $from_map->delete();
        }

        TransportCargo::deleteAll(['transport_info_id' => $this->id]);

        $cargo = $this->transportCargoInfo;
        if($cargo){
            $cargo->delete();
        }

        $user_info = $this->userInfo;
        if($user_info){
            $user_info->delete();
        }

        $dates = $this->date;
        if($dates){
            $dates->delete();
        }
        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportCargos()
    {
        return $this->hasMany(TransportCargo::className(), ['transport_info_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportCargoInfo()
    {
        return $this->hasOne(TransportCargoInfo::className(), ['id' => 'transport_cargo_info_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDate()
    {
        return $this->hasOne(Date::className(), ['id' => 'date_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToMap()
    {
        return $this->hasOne(Map::className(), ['id' => 'to_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromMap()
    {
        return $this->hasOne(Map::className(), ['id' => 'from_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportType()
    {
        return $this->hasOne(TransportType::className(), ['id' => 'transport_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(CargoUserInfo::className(), ['id' => 'user_info_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToMapOrNew()
    {
        if($this->isNewRecord){
            return new Map();
        }else{
            return $this->toMap;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromMapOrNew()
    {
        if($this->isNewRecord){
            return new Map();
        }else{
            return $this->fromMap;
        }
    }
}
