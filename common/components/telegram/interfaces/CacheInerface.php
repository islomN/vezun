<?php
namespace common\components\telegram\interfaces;

interface CacheInterface{


    public function set($key, $value);

    public function get($key);

    public function remove($key);

}