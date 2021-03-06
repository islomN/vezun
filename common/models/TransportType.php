<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transport_types".
 *
 * @property int $id
 * @property string $name
 * @property int $position
 *
 * @property CargoInfo[] $cargoInfos
 * @property TransportInfo[] $transportInfos
 */
class TransportType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transport_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position'], 'integer'],
            [['name_ru', 'name_uz', 'name_oz'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'name_uz' => 'Nomi',
            'name_oz' => 'Номи',
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargoInfos()
    {
        return $this->hasMany(CargoInfo::className(), ['transport_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportInfos()
    {
        return $this->hasMany(TransportInfo::className(), ['transport_type_id' => 'id']);
    }

    public function getName(){
        return $this->name_ru;
    }
}
