<?php
namespace common\components\telegram;

use common\components\telegram\interfaces\CacheInterface;
use yii\base\BaseObject;

class TelegramCache extends BaseObject implements CacheInterface  {

    private $chat_id;
    protected static $instance;

    public function __construct($chat_id)
    {
        $this->chat_id = $chat_id;
        return parent::__construct();
    }

    public function get($key){
        $file = \Yii::getAlias('@telegram_cache_files')."/".$this->chat_id."/".$key;

        if(file_exists($file))
            return file_get_contents($file);

        return null;
    }

    public function set($key, $value){
        $dir = \Yii::getAlias('@telegram_cache_files')."/".$this->chat_id;

        if(!is_dir($dir)){
            mkdir($dir);
        }

        $file = $dir."/".$key;

        file_put_contents($file, $value);
    }

    public function remove($key){
        @unlink(\Yii::getAlias('@telegram_cache_files')."/".$this->chat_id."/".$key);
    }


    public static function Instance($chat_id){
        if(self::$instance == null){
            self::$instance = new self($chat_id);
        }

        return self::$instance;
    }


}