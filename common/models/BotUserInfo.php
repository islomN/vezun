<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bot_user_info".
 *
 * @property int $user_info_id
 * @property int $bot_user_id
 *
 * @property UserInfo $userInfo
 * @property BotUsers $botUser
 */
class BotUserInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bot_user_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_info_id', 'bot_user_id'], 'integer'],
            [['user_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => CargoUserInfo::className(), 'targetAttribute' => ['user_info_id' => 'id']],
            [['bot_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => BotUser::className(), 'targetAttribute' => ['bot_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_info_id' => 'User Info ID',
            'bot_user_id' => 'Bot User ID',
        ];
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
    public function getBotUser()
    {
        return $this->hasOne(BotUser::className(), ['id' => 'bot_user_id']);
    }
}
