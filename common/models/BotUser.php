<?php

namespace common\models;

use common\components\telegram\Telegram;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

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

    const STATUS_ACTIVE = 1;
    const STATUS_NOTACTIVE = 0;

    private static $user;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bot_users';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression("NOW()")
            ]
        ];
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
            [['status'], 'default', 'value' => self::STATUS_ACTIVE]
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

    public static function getOne($chat_id){
        if(!self::$user){
            self::$user = self::findOne(['chat_id' => $chat_id]);
        }

        return self::$user;
    }

    public function updateUser(){
        $this->updated_at = date("Y-m-d h:i:s");
        $this->save(false);
    }
}
