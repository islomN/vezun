<?php

namespace common\models;

use common\models\map\Map;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "cargo_info".
 *
 * @property int $id
 * @property int $map_id
 * @property int $date_id
 * @property int $transport_type_id
 * @property int $cargo_id
 * @property int $user_info_id
 * @property string $created_at
 * @property int $status
 *
 * @property Date $date
 * @property Cargo $cargo
 * @property Map $map
 * @property TransportType $transportType
 * @property CargoUserInfo $userInfo
 * @property TransportCargo[] $transportCargos
 */
class CargoInfo extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_WAIT = 1;
    const STATUS_SUCCESS = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cargo_info';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at'
                ],
                'value' => new Expression("NOW()")
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['to_map_id','from_map_id', 'date_id', 'transport_type_id', 'cargo_id', 'user_info_id', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['date_id'], 'exist', 'skipOnError' => true, 'targetClass' => Date::className(), 'targetAttribute' => ['date_id' => 'id']],
            [['cargo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cargo::className(), 'targetAttribute' => ['cargo_id' => 'id']],
            [['transport_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransportType::className(), 'targetAttribute' => ['transport_type_id' => 'id']],
            [['user_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => CargoUserInfo::className(), 'targetAttribute' => ['user_info_id' => 'id']],
            [['status'], 'default', 'value' => self::STATUS_WAIT]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_map_id' => 'From Map ID',
            'to_map_id' => 'to Map ID',
            'date_id' => 'Date ID',
            'transport_type_id' => 'Transport Type ID',
            'cargo_id' => 'Cargo ID',
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

        TransportCargo::deleteAll(['cargo_info_id' => $this->id]);

        $cargo = $this->cargo;
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
    public function getDate()
    {
        return $this->hasOne(Date::className(), ['id' => 'date_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargo()
    {
        return $this->hasOne(Cargo::className(), ['id' => 'cargo_id']);
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
    public function getTransportCargos()
    {
        return $this->hasMany(TransportCargo::className(), ['cargo_info_id' => 'id']);
    }
}
