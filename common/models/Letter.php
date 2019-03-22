<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "letter".
 *
 * @property int $id
 * @property string $key
 * @property resource $word_ru
 * @property resource $word_oz
 * @property resource $word_uz
 */
class Letter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['word_ru', 'word_oz', 'word_uz','key'], 'required'],
            [['word_ru', 'word_oz', 'word_uz'], 'string'],
            [['key'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'word_ru' => 'Word Ru',
            'word_oz' => 'Word Oz',
            'word_uz' => 'Word Uz',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {

        $items = self::find()->select("key, word_uz, word_oz, word_ru")->indexBy('key')->asArray()->all();
        self::setCacheItems($items);
        return parent::afterSave($insert, $changedAttributes);
    }

    public static function getCacheItems(){
        $items =  file_get_contents(Yii::getAlias("@telegram_cache_files")."/letters");

        if($items){
            return unserialize($items);
        }

        return false;
    }


    public static function setCacheItems($items){
        return file_put_contents(Yii::getAlias("@telegram_cache_files")."/letters", serialize($items));
    }
}
