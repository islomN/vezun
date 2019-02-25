<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "weight_interval".
 *
 * @property int $id
 * @property double $from
 * @property double $to
 *
 * @property TransportCargoInfo[] $transportCargoInfos
 */
class WeightInterval extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'weight_interval';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'number'],
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
    public function getTransportCargoInfos()
    {
        return $this->hasMany(TransportCargoInfo::className(), ['weight_interval_id' => 'id']);
    }
}
