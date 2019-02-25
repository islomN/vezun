<?php
namespace common\models\main\cargointerfaces;

interface CargoInterface{

    public function save($validate = true);

    public static function find($id);

    public function getForeignItem($class, $key);

    public function setForeignItem($foreign_item);

    public static function listForeignItems();

}