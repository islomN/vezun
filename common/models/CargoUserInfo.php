<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_info".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property int $bot_user_id
 *
 * @property CargoInfo[] $cargoInfos
 * @property TransportInfo[] $transportInfos
 * @property BotUser $botUser
 */
class CargoUserInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'phone' => 'Phone',
        ];
    }

    public function beforeDelete()
    {
        BotUserInfo::deleteAll(['user_info_id' => $this->id]);
        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargoInfos()
    {
        return $this->hasMany(CargoInfo::className(), ['user_info_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportInfos()
    {
        return $this->hasMany(TransportInfo::className(), ['user_info_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBotUser()
    {
        return $this->hasOne(BotUserInfo::className(), ['user_info_id' => 'id']);
    }
}
