<?php

namespace common\models\messages;

use common\components\telegram\Telegram;
use common\models\BotUser;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string $title
 * @property string $message
 * @property string $sended_at
 *
 * @property SendMessages[] $sendMessages
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => 'sended_at'],
                'value'=> new Expression("NOW()")
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['sended_at'], 'safe'],
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
            'message' => 'Message',
            'sended_at' => 'Sended At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSendMessages()
    {
        return $this->hasMany(SendMessages::className(), ['message_id' => 'id']);
    }



    public function sendMessageToUsers($user_id = null){
        $query = BotUser::find()->select("chat_id");

        if($query){
            $query->where(['id' => $user_id]);
        }

        $list = $query->column();
        ob_start();
        foreach($list as $chat_id){
            Telegram::Instance()->sendMessage(['chat_id' => $chat_id, "text" => $this->message]);
        }

        return ob_get_clean();


    }
}
