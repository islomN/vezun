<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "volume_interval".
 *
 * @property int $id
 * @property double $from
 * @property double $to
 *
 * @property TransportCargoInfo[] $transportCargoInfos
 */
class VolumeInterval extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'volume_interval';
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
        return $this->hasMany(TransportCargoInfo::className(), ['volume_interval_id' => 'id']);
    }
}
