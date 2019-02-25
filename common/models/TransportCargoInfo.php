<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transport_cargo_info".
 *
 * @property int $id
 * @property int $weight_interval_id
 * @property int $volume_interval_id
 *
 * @property VolumeInterval $volumeInterval
 * @property WeightInterval $weightInterval
 * @property TransportInfo[] $transportInfos
 */
class TransportCargoInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transport_cargo_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['weight_interval_id', 'volume_interval_id'], 'integer'],
            [['volume_interval_id'], 'exist', 'skipOnError' => true, 'targetClass' => VolumeInterval::className(), 'targetAttribute' => ['volume_interval_id' => 'id']],
            [['weight_interval_id'], 'exist', 'skipOnError' => true, 'targetClass' => WeightInterval::className(), 'targetAttribute' => ['weight_interval_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'weight_interval_id' => 'Weight Interval ID',
            'volume_interval_id' => 'Volume Interval ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVolumeInterval()
    {
        return $this->hasOne(VolumeInterval::className(), ['id' => 'volume_interval_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWeightInterval()
    {
        return $this->hasOne(WeightInterval::className(), ['id' => 'weight_interval_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportInfos()
    {
        return $this->hasMany(TransportInfo::className(), ['transport_cargo_info_id' => 'id']);
    }
}
