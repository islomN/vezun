<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dates".
 *
 * @property int $id
 * @property string $from
 * @property string $to
 *
 * @property CargoInfo[] $cargoInfos
 * @property TransportInfo[] $transportInfos
 */
class Date extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargoInfos()
    {
        return $this->hasMany(CargoInfo::className(), ['date_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportInfos()
    {
        return $this->hasMany(TransportInfo::className(), ['date_id' => 'id']);
    }
}
