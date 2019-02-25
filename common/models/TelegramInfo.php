<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "telegram_info".
 *
 * @property string $token
 * @property string $name
 */
class TelegramInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token'], 'required'],
            [['token', 'name'], 'string', 'max' => 255],
            [['token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'token' => 'Token',
            'name' => 'Name',
        ];
    }
}
