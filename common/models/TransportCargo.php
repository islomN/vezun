<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transport_cargo".
 *
 * @property int $transport_info_id
 * @property int $cargo_info_id
 *
 * @property CargoInfo $cargoInfo
 * @property TransportInfo $transportInfo
 */
class TransportCargo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transport_cargo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transport_info_id', 'cargo_info_id'], 'integer'],
            [['cargo_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => CargoInfo::className(), 'targetAttribute' => ['cargo_info_id' => 'id']],
            [['transport_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransportInfo::className(), 'targetAttribute' => ['transport_info_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transport_info_id' => 'Transport Info ID',
            'cargo_info_id' => 'Cargo Info ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargoInfo()
    {
        return $this->hasOne(CargoInfo::className(), ['id' => 'cargo_info_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportInfo()
    {
        return $this->hasOne(TransportInfo::className(), ['id' => 'transport_info_id']);
    }
}
