<?php
namespace frontend\components\telegram;

use common\components\telegram\TelegramCache;
use common\models\BotUser;
use common\models\BotUserInfo;
use common\models\CargoInfo;
use common\models\CargoUserInfo;
use common\models\main\CargoModel;
use common\models\main\TransportModel;
use common\models\map\Country;
use common\models\map\Region;
use common\models\TransportInfo;
use common\models\TransportType;
use frontend\models\search\CargoSearch;
use frontend\models\search\TransportSearch;

class ParentTelegramAction{

    protected $lang;

    protected $hook;
    protected $letters;

    public static function init(TelegramHook $telegramHook){
        $self = new static;
        $self->hook = $telegramHook;
        $self->hook->action = $self;
        $self->hook->page = new TelegramPage($self->hook);
        $self->hook->lang = $self->getLang() ? : "ru";

        $self->action();
    }



    protected  function countryDefault($next_method, $msg_key, $key, $new, $skip = true){

        $this->mapDefault($next_method, $key, "country",$new);
        $page = $this->getPaginationValue(Country::class, "country", $new);

        $this->setCache("offset_country", $page);
        $this->hook->page->country($msg_key, $page, $skip);
    }

    protected  function regionDefault($next_method, $msg_key, $key, $new, $skip = true){
        $this->mapDefault($next_method, $key, "region",$new);
        $condition = ['regions.country_id' => $this->getCache("country"."_".$key)];
        $page = $this->getPaginationValue(
            Region::class,
            "region", $new,
            $condition
        );

        $this->setCache("offset_country", $page);

        $this->hook->page->region($msg_key,$condition, $page,$skip);
    }

    protected  function mapDefault($next_method, $key, $type,$new){

        if($new){
            $this->removeCache($type."_".$key);
            return false;
        }

        $id_method = $type."ID";
        $id = $this->$id_method($new);

        if(!$id){
            return false;
        }

        $this->setCache($type."_".$key,$id);
        $this->clearMapOffset($type);
        $this->$next_method(true);
        exit;
    }

    protected  function countryID(){
        return $this->mapID(Country::class);

    }

    protected  function regionID(){
        return $this->mapID(Region::class);
    }

    protected  function mapID($class){
        $item = $class::find()->select("id")
            ->where(['name' => $this->hook->text])
            ->one();
        if($item){
            return $item->id;
        }

        return false;
    }

    protected  function clearMapOffset($key){
        $this->removeCache("offset_".$key);
    }


    protected function transportID(){
        $item = TransportType::find()->select("id")
            ->where(['name_'.$this->hook->lang => $this->hook->text])
            ->one();
        if($item){
            return $item->id;
        }

        return false;
    }

    protected  function back($method, $new){
        if(!$new && $this->hook->text == $this->hook->getLetter("back_btn")){
            $this->$method(true);
            exit;
        }
    }

    protected  function skip($method, $new){
        if(!$new && $this->hook->text == $this->hook->getLetter("skip_btn")){
            $this->$method(true);
            exit;
        }
    }


    protected  function getAction(){
        return TelegramCache::Instance($this->hook->chat_id)->get("action");

    }

    protected  function setAction($value){
        return TelegramCache::Instance($this->hook->chat_id)->set("action", $value);
    }

    protected  function getLang(){
        return TelegramCache::Instance($this->hook->chat_id)->get("lang");

    }

    protected  function setLang($value){
        return TelegramCache::Instance($this->hook->chat_id)->set("lang", $value);
    }

    public  function getCache($key){
        return TelegramCache::Instance($this->hook->chat_id)->get($key);
    }

    protected  function setCache($key, $value){
        return TelegramCache::Instance($this->hook->chat_id)->set($key, $value);
    }

    protected  function removeCache($key){
        return TelegramCache::Instance($this->hook->chat_id)->remove($key);
    }


    protected  function getWordLang(){
        return "word_".$this->getLang();
    }


    protected  function runAction(){
        $page = $this->getAction();
        if(!$page){
            $page = "main";
        }

        $this->$page();
    }


    protected  function getPaginationValue($class, $name, $new = false, $condition = [] ){
        $cache_name = 'offset_'.$name;
        if($new){
            $this->setCache($cache_name, 1);
            return 1;
        }

        $page = $this->getCache($cache_name);
        if(!$page){
            $page = 1;
        }

        if($this->hook->text == $this->hook->getLetter("next_btn")){
            $pages = $this->getCountPage($class, $condition, $name);

            if($page == $pages){
                $page = 1;
            }else{
                $page++;
            }
        }elseif($this->hook->text == $this->hook->getLetter("prev_btn")){
            $pages = $this->getCountPage($class, $condition, $name);
            if($page == 1){
                $page = $pages;
            }else{
                $page--;
            }
        }


        return $page;
    }

    protected  function getCountPage($class, $condition = [], $name = "country"){
        $query = $class::find()->select('count('.$class::tableName().'.id) as count');
        if($this->getCache("type") == "search"){
            $query->join("join","map", $class::tableName().".id = {$name}_id");
        }

        if($condition){
            $query->andWhere($condition);
        }

        $count = $query->asArray()->one()['count'];

        return ceil($count/$this->hook->limit);
    }


    protected  function setNumberValue($type, $new){
        if($new)
            return;
        if(!$this->isNumeric($new)){
            return false;
        }

        $this->setCache($type, $this->hook->text);
        return true;
    }

    protected function validateValueWithSibling($sibling_key){
        $sibling_value = $this->getCache($sibling_key);
        $current_value = $this->hook->text;

        return $current_value >= $sibling_value;
    }

    protected  function isNumeric(){


        if(!is_numeric($this->hook->text)){
            return false;
        }

        return $this->hook->text;
    }

    protected  function getPhoneNumber(){

        if(isset($this->hook->input->message->contact->phone_number)){
            return $this->hook->input->message->contact->phone_number;
        }elseif($this->isPhoneNumber()){
            return $this->hook->text;
        }
        return false;
    }

    protected function isPhoneNumber(){
        $reg = '/\+?\d{7,12}/';
        if(preg_match($reg, $this->hook->text)){
            return true;
        }

        return false;
    }

    protected function setDate($cache_key, $new){
        if($new){
            return false;
        }

        if($this->isDate()){
            $this->setCache($cache_key, $this->hook->text);
            return true;
        }

        return false;
    }

    protected function validateDateWithSibling($sibling_key){
        $sibling_value = strtotime($this->getCache($sibling_key));
        $current_value = strtotime($this->hook->text);

        return $current_value >= $sibling_value;
    }

    protected function isDate(){
        return strtotime($this->hook->text);
    }

    protected function getPaginationValueForDate($new){
        $cache_name = 'offset_date';
        if($new){
            $this->setCache($cache_name, 1);
            return 1;
        }

        $page = $this->getCache($cache_name);
        if($page<1 | !$page){
            $page = 1;
        }

        if($this->hook->text == $this->hook->getLetter("next_btn")){

            $page++;
        }elseif($this->hook->text == $this->hook->getLetter("prev_btn")){

            $page--;
        }


        return $page;
    }
    protected function removeForeignCacheFiles(){
        $not_remove_files = ['action', 'type', 'section', 'lang'];
        $this->removeAllCacheFiles($not_remove_files);
    }
    protected function removeAllCacheFiles($not_remove_files){

        TelegramCache::Instance($this->hook->chat_id)->removeAll($not_remove_files);
    }


    protected function userOperation(){
        if($this->isNewUser()){
            $this->saveUser();
        }elseif(!$this->isUserIssetDoctype()){
            return false;
        }else{
            $this->updateUserStatistic();
        }

        return true;
    }

    protected function isUserIssetDoctype(){
        $userModel = BotUser::getOne($this->hook->chat_id);

        if($userModel && $userModel->status != BotUser::STATUS_ACTIVE){
            return true;
        }

        return false;
    }

    public function isNewUser(){
        $userModel = BotUser::getOne($this->hook->chat_id);

        if($userModel){
            return false;
        }

        return true;
    }

    protected function saveUser(){
        $userModel = new BotUser();
        $userModel->chat_id = $this->hook->chat_id;
        $userModel->nickname = $this->hook->username;
        $userModel->name = $this->hook->fullname;
        $userModel->save(false);

        return true;
    }

    protected function updateUserStatistic(){
        BotUser::getOne($this->hook->chat_id)->updateUser();
    }

    protected function generateTextForAdd($section){
        $method = "generateTextForAdd".ucfirst($section);
        return $this->$method();
    }

    protected function generateTextForSearch($section){

    }

    protected function generateTextForAddCargo(){
        return "a";
    }

    protected function sendCargoItem(CargoInfo $item){
        $text = $this->replaceForCargo($item);
        return $this->hook->page->sendMessage($text);
    }

    protected function sendTransportItem(TransportInfo $item){
        $text = $this->replaceForTransport($item);
        return $this->hook->page->sendMessage($text);
    }

    protected function addTransport($id = null){

        $model = new TransportModel($id);
        $model->from_country_id = $this->getCache("country_from");
        $model->from_region_id = $this->getCache("region_from");
        $model->to_country_id = $this->getCache("country_to");
        $model->to_region_id = $this->getCache("region_to");
        $model->weight_from = $this->getCache("weight_from");
        $model->weight_to = $this->getCache("weight_to");
        $model->volume_from = $this->getCache("volume_from");
        $model->volume_to = $this->getCache("volume_to");
        $model->transport_type_id = $this->getCache('transport_type');
        $model->from = date("Y-m-d", strtotime($this->getCache("start_date")));
        $model->to = date("Y-m-d", strtotime($this->getCache("finish_date")));
        $model->name = $this->getCache('fullname');
        $model->phone = $this->getCache("phone");
        $model->bot_user_id = BotUser::findOne(['chat_id' => $this->hook->chat_id])->id;
        return $model->save();
    }

    protected function addCargo($id){
        $model = new CargoModel($id);
        $model->from_country_id = $this->getCache("country_from");
        $model->from_region_id = $this->getCache("region_from");
        $model->to_country_id = $this->getCache("country_to");
        $model->to_region_id = $this->getCache("region_to");
        $model->weight = $this->getCache("cargo_weight");
        $model->volume = $this->getCache("cargo_volume");
        $model->title = $this->getCache("cargo_name");
        $model->from = date("Y-m-d", strtotime($this->getCache("start_date")));
        $model->to = date("Y-m-d", strtotime($this->getCache("finish_date")));
        $model->transport_type_id = $this->getCache('transport_type');
        $model->name = $this->getCache('fullname');
        $model->phone = $this->getCache("phone");
        $model->bot_user_id = BotUser::findOne(['chat_id' => $this->hook->chat_id])->id;
        return  $model->save();
    }

    protected function getModelSearchCargo(){

        $model = new CargoSearch();
        $model->from_country_id = $this->getCache("country_from");
        $model->from_region_id = $this->getCache("region_from");
        $model->to_country_id = $this->getCache("country_to");
        $model->to_region_id = $this->getCache("region_to");
        $model->weight_from = $this->getCache('weight_from');
        $model->weight_to = $this->getCache('weight_to');
        $model->volume_from = $this->getCache('volume_from');
        $model->volume_to = $this->getCache('volume_to');

        return $model->search()->orderBy("created_at desc");
    }

    protected function searchCargo($page = 1){
        $model = $this->getModelSearchCargo();

        return $model->limit($this->hook->limit)
                                ->offset(($page-1)*$this->hook->limit)
                                ->all();
    }

    protected function getCountSearchCargo(){
        return $this->getModelSearchCargo()->count();
    }

    protected function getModelsearchTransport(){
        $model = new TransportSearch();
        $model->from_country_id = $this->getCache("country_from");
        $model->from_region_id = $this->getCache("region_from");
        $model->to_country_id = $this->getCache("country_to");
        $model->to_region_id = $this->getCache("region_to");
        $model->weight_from = $this->getCache('weight_from');
        $model->weight_to = $this->getCache('weight_to');
        $model->volume_from = $this->getCache('volume_from');
        $model->volume_to = $this->getCache('volume_to');

        return $model->search()->orderBy("created_at desc");
    }

    protected function getCountSearchTransport(){
        return $this->getModelsearchTransport()->count();
    }

    protected function searchTransport($page = 1){
        $model = $this->getModelsearchTransport();

        return $model->limit($this->hook->limit)
            ->offset(($page-1)*$this->hook->limit)
            ->all();
    }

    protected function viewTransport(TransportInfo $item){
        $search = [
            "{{from_map}}",
            "{{to_map}}",
            "{{type_transport}}",
            "{{date_from}}",
            "{{date_to}}",
            "{{weight_from}}",
            "{{weight_to}}",
            "{{volume_from}}",
            "{{volume_to}}",
            "{{name}}",
            "{{phone}}",
        ];
        $empty_value = $this->hook->getLetter("empty_msg");
        $replace = [
            "{{from_map}}" => ($map = $item->fromMap) ? $map->getFullInfo(): $empty_value,

            "{{to_map}}" => ($map = $item->toMap) ? $map->getFullInfo(): $empty_value,

            "{{type_transport}}" => ($type = $item->transportType) ? $type->{"name_".$this->hook->lang} : $empty_value,

            "{{date_from}}" => ($date = $item->date) ? $date->from : $empty_value,

            "{{date_to}}" => ($date) ? $date->to : $empty_value,

            "{{weight_from}}" => ($weight = $item->weightInterval) ? $weight->from : $empty_value,

            "{{weight_to}}" => ($weight) ? $weight->to : $empty_value,

            "{{volume_from}}" => ($volume = $item->volumeInterval) ? $volume->from : $empty_value,

            "{{volume_to}}" => ($volume ) ? $volume->to : $empty_value,

            "{{name}}" => ($user = $item->userInfo) ? $user->name : $empty_value,

            "{{phone}}" => ($user) ? $user->phone : $empty_value,
        ];
        $subject = $this->hook->getLetter("transport_list_items_msg");
        return $this->replaceText($search, $replace, $subject);
    }


    protected function viewCargo(CargoInfo $item){
        $search = [
            "{{from_map}}",
            "{{to_map}}",
            "{{type_transport}}",
            "{{date_from}}",
            "{{date_to}}",
            "{{title}}",
            "{{weight}}",
            "{{volume}}",
            "{{name}}",
            "{{phone}}",
        ];

        $empty_value = $this->hook->getLetter("empty_msg");
        $replace = [
            "{{from_map}}" => ($map = $item->fromMap) ? $map->getFullInfo(): $empty_value,

            "{{to_map}}" => ($map = $item->toMap) ? $map->getFullInfo(): $empty_value,

            "{{type_transport}}" => ($type = $item->transportType) ? $type->{"name_".$this->hook->lang} : $empty_value,

            "{{date_from}}" => ($date = $item->date) ? $date->from : $empty_value,

            "{{date_to}}" => ($date) ? $date->to : $empty_value,

            "{{title}}" => ($cargo = $item->cargo) ? $cargo->title : $empty_value,

            "{{weight}}" => ($cargo) ? $cargo->weight : $empty_value,

            "{{volume}}" => ($cargo ) ? $cargo->volume : $empty_value,

            "{{name}}" => ($user = $item->userInfo) ? $user->name : $empty_value,

            "{{phone}}" => ($user) ? $user->phone : $empty_value,
        ];
        $subject = $this->hook->getLetter("view_cargo_item_msg");
        return $this->replaceText($search, $replace, $subject);
    }

    protected function previewTransport(){
        $search = [
            "{{from_map}}",
            "{{to_map}}",
            "{{type_transport}}",
            "{{date_from}}",
            "{{date_to}}",
            "{{weight_from}}",
            "{{weight_to}}",
            "{{volume_from}}",
            "{{volume_to}}",
            "{{name}}",
            "{{phone}}",
        ];

        $from_map = Country::findOne($this->getCache("country_from"))->name . ", ".Region::findOne($this->getCache("region_from"))->name;
        $from_to = Country::findOne($this->getCache("country_to"))->name . ", ".Region::findOne($this->getCache("region_to"))->name;
        $type_transport = TransportType::findOne($this->getCache("transport_type"))->{"name_".$this->hook->lang};

        $date_from = $this->getCache("start_date");
        $date_to = $this->getCache("finish_date");
        $weight_from = $this->getCache("weight_from");
        $weight_to = $this->getCache("weight_to");
        $volume_from = $this->getCache("volume_from");
        $volume_to = $this->getCache("volume_to");
        $name = $this->getCache("fullname");
        $phone = $this->getCache("phone");


        $replace = [
            "{{from_map}}" => $from_map,

            "{{to_map}}" => $from_to,

            "{{type_transport}}" => $type_transport,

            "{{date_from}}" => $date_from,

            "{{date_to}}" => $date_to,

            "{{weight_from}}" => $weight_from,

            "{{weight_to}}" => $weight_to,

            "{{volume_from}}" => $volume_from,

            "{{volume_to}}" => $volume_to,

            "{{name}}" => $name,

            "{{phone}}" => $phone,
        ];


        $subject = $this->hook->getLetter("transport_list_items_msg");

        return $this->replaceText($search, $replace, $subject);
    }


    protected function previewCargo(){
        $search = [
            "{{from_map}}",
            "{{to_map}}",
            "{{type_transport}}",
            "{{date_from}}",
            "{{date_to}}",
            "{{title}}",
            "{{weight}}",
            "{{volume}}",
            "{{name}}",
            "{{phone}}",
        ];

        $from_map = Country::findOne($this->getCache("country_from"))->name . ", ".Region::findOne($this->getCache("region_from"))->name;
        $from_to = Country::findOne($this->getCache("country_to"))->name . ", ".Region::findOne($this->getCache("region_to"))->name;
        $type_transport = TransportType::findOne($this->getCache("transport_type"))->{"name_".$this->hook->lang};

        $date_from = $this->getCache("start_date");
        $date_to = $this->getCache("finish_date");
        $weight = $this->getCache("cargo_weight");
        $volume = $this->getCache("cargo_volume");
        $name = $this->getCache("fullname");
        $phone = $this->getCache("phone");
        $title = $this->getCache("cargo_name");

        $replace = [
            "{{from_map}}" => $from_map,

            "{{to_map}}" => $from_to,

            "{{type_transport}}" => $type_transport,

            "{{date_from}}" => $date_from,

            "{{date_to}}" => $date_to,

            "{{title}}" => $title,

            "{{weight}}" => $weight,

            "{{volume}}" => $volume,

            "{{name}}" => $name,

            "{{phone}}" => $phone,
        ];



        $subject = $this->hook->getLetter("cargo_list_msg");

        return $this->replaceText($search, $replace, $subject);
    }

    protected function searchItem($section){
        $method = "searchItem".ucfirst($section);
        return $this->$method();
    }

    protected function searchItemTransport(){
        $search = [
            "{{from_map}}",
            "{{to_map}}",
            "{{type_transport}}",

            "{{weight_from}}",
            "{{weight_to}}",
            "{{volume_from}}",
            "{{volume_to}}"
        ];

        $from_map = Country::findOne($this->getCache("country_from"))->name . ", ".Region::findOne($this->getCache("region_from"))->name;
        $from_to = Country::findOne($this->getCache("country_to"))->name . ", ".Region::findOne($this->getCache("region_to"))->name;
        $type_transport = TransportType::findOne($this->getCache("transport_type"))->{"name_".$this->hook->lang};

        $weight_from = $this->getCache("weight_from");
        $weight_to = $this->getCache("weight_to");
        $volume_from = $this->getCache("volume_from");
        $volume_to = $this->getCache("volume_to");

        $empty_value = $this->hook->getLetter("empty_msg");
        $replace = [
            "{{from_map}}" => $from_map != ", " ?: $empty_value,

            "{{to_map}}" => $from_to != ", " ?:  $empty_value,

            "{{type_transport}}" => $type_transport ?: $empty_value,

            "{{weight_from}}" => $weight_from ?: $empty_value,

            "{{weight_to}}" => $weight_to ?: $empty_value,

            "{{volume_from}}" => $volume_from ?: $empty_value,

            "{{volume_to}}" => $volume_to ?: $empty_value
        ];


        $subject = $this->hook->getLetter("search_transport_item_msg");

        return $this->replaceText($search, $replace, $subject);
    }


    protected function searchItemCargo(){
        $search = [
            "{{from_map}}" ,

            "{{to_map}}",

            "{{weight_from}}",

            "{{weight_to}}",

            "{{volume_from}}",
            "{{volume_to}}"
        ];

        $from_map = Country::findOne($this->getCache("country_from"))->name . ", ".Region::findOne($this->getCache("region_from"))->name;
        $from_to = Country::findOne($this->getCache("country_to"))->name . ", ".Region::findOne($this->getCache("region_to"))->name;

        $empty_value = $this->hook->getLetter("empty_msg");

        $replace = [
            "{{from_map}}" => $from_map != ", " ?:  $empty_value,

            "{{to_map}}" => $from_to != ", " ?:  $empty_value,


            "{{weight_from}}" => $this->getCache("weight_from") ?: $empty_value,

            "{{weight_to}}" => $this->getCache("weight_to")? : $empty_value,

            "{{volume_from}}" => $this->getCache("volume_from") ?: $empty_value,
            "{{volume_to}}" => $this->getCache("volume_to") ?: $empty_value
        ];



        $subject = $this->hook->getLetter("search_cargo_item_msg");

        return $this->replaceText($search, $replace, $subject);
    }

    protected function replaceText($search, $replace,  $subject){
        foreach($search as $item){
            $subject = str_replace($item, $replace[$item], $subject);
        }
        return $subject;
    }


    protected function myCargoList(){
        return CargoInfo::find()->select('id')
                ->where([
                    'in',
                    'user_info_id',
                    CargoUserInfo::find()->select('id')
                        ->where([
                                'in',
                                'id',
                                BotUserInfo::find()->select('user_info_id')->where([
                                    'in','bot_user_id', BotUser::find()
                                                    ->select('id')
                                                    ->where(['chat_id' => $this->hook->chat_id])
                                ])])
                ])->orderBy('created_at desc')
                ->column();

    }

    protected function myTransportList(){
        return TransportInfo::find()->select('id')
            ->where([
                'in',
                'user_info_id',
                CargoUserInfo::find()->select('id')
                    ->where([
                        'in',
                        'id',
                        BotUserInfo::find()->select('user_info_id')->where([
                            'in','bot_user_id', BotUser::find()
                                ->select('id')
                                ->where(['chat_id' => $this->hook->chat_id])
                        ])])
            ])->orderBy('created_at desc')
            ->column();

    }

    protected function getMyStatistic(){
        $text = $this->hook->getLetter('my_statistic_msg');

        $cargo = $this->myCargoList();
        $transport = $this->myTransportList();

        if($transport){
            $this->setCache("my_transport_list", implode(",", $transport));
        }

        if($cargo){
            $this->setCache("my_cargo_list", implode(",", $cargo));
        }

        $text = str_replace(["{{cargo}}", "{{transport}}"], [count($cargo), count($transport)], $text);

        $this->removeCache("current_cargo");
        $this->removeCache("current_transport");
        $this->removeCache("current_cargo_page");
        $this->removeCache("current_transport_page");
        return $text;
    }

    protected function getFirstItem($key){
        $list = explode(",", $this->getCache("my_{$key}_list"));


        return $list[0];
    }

    protected function getCargoItem($id){

        $item = CargoInfo::find()->where(['id' => $id])->one();

        return $this->viewCargo($item);
    }

    protected function getTransportItem($id){
        $item = TransportInfo::find()->where(['id' => $id])->one();

        return $this->viewTransport($item);
    }


    protected  function nextItem($key){
        $cargo_page = $this->getCache("current_{$key}_page") ? : 0;


        $list_cargo = explode(",", $this->getCache("my_{$key}_list"));
        $count = count($list_cargo);
        $id = null;

        if(($count-1) > $cargo_page){
            $cargo_page ++;
        }else{
            $cargo_page = 0;
        }




        $id = $list_cargo[$cargo_page];

        $this->setCache("current_{$key}_page", $cargo_page);
        $this->setCache("current_{$key}", $id);
        return $id;

    }

    protected  function prevItem($key){
        $cargo_page = $this->getCache("current_{$key}_page") ? : 0;


        $list_cargo = explode(",", $this->getCache("my_{$key}_list"));
        $id = null;

        if($cargo_page <= 0){
            $cargo_page = count($list_cargo) - 1;
        }else{
            $cargo_page --;
        }

        $id = $list_cargo[$cargo_page];

        $this->setCache("current_{$key}_page", $cargo_page);
        $this->setCache("current_{$key}", $id);
        return $id;

    }
}
