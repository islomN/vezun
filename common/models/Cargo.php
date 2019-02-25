<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cargo".
 *
 * @property int $id
 * @property string $title
 * @property double $weight
 * @property double $volume
 *
 * @property CargoInfo[] $cargoInfos
 */
class Cargo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cargo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['weight', 'volume'], 'number'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'weight' => 'Weight',
            'volume' => 'Volume',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargoInfos()
    {
        return $this->hasMany(CargoInfo::className(), ['cargo_id' => 'id']);
    }
}
