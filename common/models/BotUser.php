<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bot_users".
 *
 * @property int $id
 * @property string $chat_id
 * @property string $name
 * @property string $phone
 * @property string $nickname
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 *
 * @property UserInfo[] $userInfos
 */
class BotUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bot_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['chat_id', 'name', 'phone', 'nickname'], 'string', 'max' => 255],
            [['chat_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'nickname' => 'Nickname',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfos()
    {
        return $this->hasMany(CargoUserInfo::className(), ['bot_user_id' => 'id']);
    }
}
