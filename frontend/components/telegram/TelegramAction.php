<?php
namespace frontend\components\telegram;

use common\models\map\Country;
use common\models\searchmodels\CargoInfo;
use common\models\searchmodels\TransportInfo;
use common\models\searchmodels\TransportType;

class TelegramAction extends ParentTelegramAction
{

    public function action(){

        $res = $this->userOperation();

        if(!$res){
            return false;
        }

        switch($this->hook->text){
            case "/start":
                $msg =  $this->hook->getLetter('welcome_msg');
                $this->hook->page->sendMessage($msg);
                $this->main();
                break;
            case $this->hook->getLetter("main_btn"):
                $this->main();
                break;

            case $this->hook->getLetter('lang_btn'):
            case "/lang":
                return $this->lang(true);
                break;

            default:
                return $this->runAction();
        }
    }

    public function main($new = false){


        $this->removeForeignCacheFiles();
        if(!$new){

            switch($this->hook->text){
                case $this->hook->getLetter('main_search_cargo_btn'):
                    $this->setCache("section", "cargo");
                    $this->setCache("type", "search");
                    return $this->cargoCountryFromSearch(true);
                    break;

                case $this->hook->getLetter('main_search_transport_btn'):
                    $this->setCache("section", "transport");
                    $this->setCache("type", "search");
                    $this->transportCountryFromSearch(true);
                    break;
                case $this->hook->getLetter('add_cargo_btn'):
                    $this->setCache("section", "cargo");
                    $this->setCache("type", "add");
                    $this->cargoCountryFromAdd(true);
                    break;
                case $this->hook->getLetter('add_transport_btn'):
                    $this->setCache("section", "transport");
                    $this->setCache("type", "add");
                    $this->transportCountryFromAdd(true);
                    break;

                case $this->hook->getLetter('my_profile_btn'):

                    $this->profile(true);
                    break;
                default:
                    $this->hook->page->main();

                    return $this->setAction("main");
            }
        }else{
            $this->hook->page->main();
            $this->setAction("main");
        }



    }

    public function lang($new = false){
        $this->back("main", $new);
        $langs = Lang::getList();

        if(!$new && ($key = array_search($this->hook->text, $langs)) !== false)
        {
            $this->hook->page->sendMessage($key);
            $this->setLang($key);
            $this->hook->lang = $key;
            $this->hook->wordlang = "word_".$key;
            return $this->main();
        }

        $this->hook->page->lang();

        return $this->setAction("lang");
    }

    public function myProfile(){

    }

    /*************************** cargo search ********************************/

    public function cargoCountryFromSearch($new = false){

        $this->back("main", $new);
        $this->skip("countryToSearchCargo", $new);

        $this->countryDefault("cargoRegionFromSearch","country_from_search_cargo_btn","from", $new);
        $this->setAction("cargoCountryFromSearch");
        return true;
    }

    public function cargoRegionFromSearch($new = false){

        $this->back("cargoCountryFromSearch", $new);
        $this->skip("countryToSearchCargo", $new);

        $this->regionDefault("countryToSearchCargo","region_from_search_cargo_btn","from", $new);
        $this->setAction("cargoRegionFromSearch");
        return true;
    }

    public function countryToSearchCargo($new = false){
        $this->back("main", $new);
        $this->skip("cargoWeightFromSearch", $new);

        $this->countryDefault("cargoRegionToSearch","country_from_search_cargo_btn","to", $new);
        $this->setAction("countryToSearchCargo");
        return true;
    }

    public function cargoRegionToSearch($new = false){
        $this->back("countryToSearchCargo", $new);
        $this->skip("cargoWeightFromSearch", $new);

        $this->regionDefault("cargoWeightFromSearch","region_to_search_cargo_btn","to", $new);
        $this->setAction("cargoRegionToSearch");
        return true;
    }

    public function cargoWeightFromSearch($new = false){
        if($new){
            $this->removeCache("weight_from");
        }
        $this->skip("cargoWeightToSearch", $new);
        $this->back("countryToSearchCargo", $new);

        $res = $this->setNumberValue("weight_from", $new);
        if($res){
            $this->cargoWeightToSearch(true);
            return ;
        }

        $this->hook->page->onlyEnterPage("search_weight_from_btn");
        $this->setAction("cargoWeightFromSearch");
    }

    public function cargoWeightToSearch($new = false){
        if($new){
            $this->removeCache("weight_to");
        }
        $this->back("cargoWeightToSearch", $new);
        $this->skip("cargoVolumeFromSearch", $new);


        $res = $this->setNumberValue("weight_to", $new);

        if($res){
            $this->cargoVolumeFromSearch(true);
            return ;
        }

        $this->hook->page->onlyEnterPage("search_weight_to_btn");
        $this->setAction("cargoWeightToSearch");
    }

    public function cargoVolumeFromSearch($new = false){
        if($new){
            $this->removeCache("volume_from");
        }
        $this->back("cargoWeightToSearch", $new);
        $this->skip("cargoVolumeToSearch", $new);

        $res = $this->setNumberValue("volume_from", $new);

        if($res){
            $this->cargoVolumeToSearch(true);
            return ;
        }

        $this->hook->page->onlyEnterPage("search_volume_from_btn");
        $this->setAction("cargoVolumeFromSearch");
    }

    public function cargoVolumeToSearch($new = false){
        if($new){
            $this->removeCache("volume_to");
        }

        $this->back("cargoVolumeFromSearch", $new);
        $this->skip("search", $new);

        $res = $this->setNumberValue("volume_to", $new)
                && $this->validateValueWithSibling('volume_from');
        if($res){
            return $this->search(true);
        }

        $this->hook->page->onlyEnterPage("search_volume_to_btn");
        $this->setAction("cargoVolumeToSearch");
    }

    /*************************** cargo search ********************************/

    /*************************** transport search ********************************/
    public function transportCountryFromSearch($new = false){
        $this->back("main", $new);
        $this->skip("transportCountryToSearch", $new);

        $this->countryDefault("transportRegionFromSearch","country_from_search_transport_btn","from", $new);
        $this->setAction("transportCountryFromSearch");
        return true;
    }

    public function transportRegionFromSearch($new = false){
        $this->back("transportCountryFromSearch", $new);
        $this->skip("transportCountryToSearch", $new);

        $this->regionDefault("transportCountryToSearch","region_from_search_cargo_btn","from", $new);
        $this->setAction("transportRegionFromSearch");
        return true;
    }

    public function transportCountryToSearch($new = false){
        $this->back("main", $new);
        $this->skip("transportTypeSearch", $new);

        $this->countryDefault("transportRegionToSearch","country_from_search_cargo_btn","to", $new);
        $this->setAction("transportCountryToSearch");
        return true;
    }

    public function transportRegionToSearch($new = false){
        $this->back("transportCountryToSearch", $new);
        $this->skip("transportTypeSearch", $new);

        $this->regionDefault("transportTypeSearch","region_to_search_cargo_btn","to", $new);
        $this->setAction("transportRegionToSearch");
        return true;
    }

    public function transportTypeSearch($new = false){
        $this->back("transportCountryToSearch", $new);
        $this->skip("transportWeightFromSearch", $new);

        $page = $this->getPaginationValue(TransportType::class, "transport_type", $new);

        if(!$new){
            if($id = $this->transportID()){
                $this->setCache("transport_type", $id);
                return $this->transportWeightFromSearch(true);
            }
        }

        $this->hook->page->transportType("choose_transport_type_msg", $page);
        $this->setAction("transportTypeSearch");
        return true;
    }

    public function transportWeightFromSearch($new = false){

        if($new){
            $this->removeCache("weight_from");
        }

        $this->back("transportTypeSearch", $new);
        $this->skip("transportWeightToSearch", $new);

        $res = $this->setNumberValue("weight_from", $new);

        if($res){
            $this->transportWeightToSearch(true);
            return ;
        }

        $this->hook->page->onlyEnterPage("search_weight_from_btn");

        $this->setAction("transportWeightFromSearch");
        return true;
    }

    public function transportWeightToSearch($new = false){
        if($new){
            $this->removeCache("volume_to");
        }
        $this->back("transportWeightFromSearch", $new);
        $this->skip("transportVolumeFromSearch", $new);

        $res = $this->setNumberValue("volume_to", $new) && $this->validateValueWithSibling("volume_from");

        if($res){
            $this->transportVolumeFromSearch(true);
            return ;
        }

        $this->hook->page->onlyEnterPage("search_weight_to_btn");

        $this->setAction("transportWeightToSearch");
        return true;
    }

    public function transportVolumeFromSearch($new = false){
        if($new){
            $this->removeCache("volume_from");
        }
        $this->back("transportWeightToSearch", $new);
        $this->skip("transportVolumeToSearch", $new);

        $res = $this->setNumberValue("volume_from", $new);

        if($res){
            $this->transportVolumeToSearch(true);
            return ;
        }

        $this->hook->page->onlyEnterPage("search_volume_from_btn");

        $this->setAction("transportVolumeFromSearch");
        return true;
    }

    public function transportVolumeToSearch($new = false){
        if($new){
            $this->removeCache("volume_to");
        }

        $this->back("countryToSearchCargo", $new);
        $this->skip("search", $new);

        $res = $this->setNumberValue("volume_to", $new);
        if($res){
            return $this->search(true);

        }

        $this->hook->page->onlyEnterPage(
            "search_volume_to_btn"
        );


        $this->setAction("transportVolumeToSearch");
        return true;
    }

    /*************************** transport search ********************************/


    /*************************** transport add ********************************/

    public function transportCountryFromAdd($new = false){
        $this->back("main", $new);

        if($new){
            $this->hook->page->sendMessage($this->hook->getLetter('add_transport_msg'));
            $this->hook->page->sendMessage($this->hook->getLetter("from_map_msg"));
        }

        $this->countryDefault("transportRegionFromAdd","country_btn","from", $new, false);
        $this->setAction("transportCountryFromAdd");
        return true;
    }

    public function transportRegionFromAdd($new = false){
        $this->back("transportCountryFromAdd", $new);

        $this->regionDefault("transportCountryToAdd","region_btn","from", $new, false);
        $this->setAction("transportRegionFromAdd");
        return true;
    }

    public function transportCountryToAdd($new = false){
        $this->back("main", $new);
        if($new){
            $this->hook->page->sendMessage($this->hook->getLetter("to_map_msg"));
        }
        $this->countryDefault("transportRegionToAdd","country_btn","to", $new, false);
        $this->setAction("transportCountryToAdd");
        return true;
    }

    public function transportRegionToAdd($new = false){
        $this->back("transportCountryToAdd", $new);

        $this->regionDefault("transportTypeAdd","region_btn","to", $new,  false);
        $this->setAction("transportRegionToAdd");
        return true;
    }

    public function transportTypeAdd($new = false){
        $this->back("transportRegionToAdd", $new);

        $page = $this->getPaginationValue(TransportType::class, "transport_type", $new);

        if(!$new && $id = $this->transportID()){
            $this->setCache("transport_type",$id);
            $this->transportAddStartDateAdd(true);
            return true;
        }

        $this->hook->page->transportType("choose_transport_type_msg", $page, false);
        $this->setAction("transportTypeAdd");
        return true;
    }

    public function transportAddStartDateAdd($new = false){
        if($new){
            $this->removeCache("offset_date");
        }
        $this->back("transportTypeAdd", $new);
        $page = $this->getPaginationValueForDate($new);

        if($this->setDate("start_date", $new)){

            return $this->transportAddFinishDate(true);
        }

        $this->hook->page->date("transport_date_start_msg",$page);
        $this->setCache("offset_date", $page);
        $this->setAction("transportAddStartDateAdd");
    }

    public function transportAddFinishDate($new = false){
        if($new){
            $this->removeCache("offset_date");
        }

        $this->back("transportAddStartDateAdd", $new);
        $page = $this->getPaginationValueForDate($new);

        if($this->setDate("finish_date", $new) && $this->validateDateWithSibling("start_date")){
            return $this->transportAddFromWeight(true);
        }

        $this->hook->page->date("transport_date_finish_msg",$page);
        $this->setCache("offset_date", $page);
        $this->setAction("transportAddFinishDate");
    }

    public function transportAddFromWeight($new = false){
        $this->back("transportAddFinishDate", $new);

        $res = $this->setNumberValue("weight_from", $new);

        if($res){
            $this->transportAddToWeight(true);
            return ;
        }


        $this->hook->page->onlyEnterPage("transport_add_weight_from_btn", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT, false);
        $this->setAction("transportAddFromWeight");
    }

    public function transportAddToWeight($new = false){
        $this->back("transportAddFromWeight", $new);

        $res = $this->setNumberValue("weight_to", $new)
                && $this->validateValueWithSibling('weight_from');

        if($res){
            $this->transportAddFromVolume(true);
            return ;
        }


        $this->hook->page->onlyEnterPage("transport_add_weight_to_btn", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT, false);
        $this->setAction("transportAddToWeight");
    }

    public function transportAddFromVolume($new = false){
        $this->back("transportAddToWeight", $new);
        $res = $this->setNumberValue("volume_from", $new);

        if($res){
            $this->transportAddToVolume(true);
            return ;
        }

        $this->hook->page->onlyEnterPage("transport_add_volume_from_btn", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT, false);
        $this->setAction("transportAddFromVolume");
    }

    public function transportAddToVolume($new = false){
        $this->back("transportAddFromVolume", $new);
        $res = $this->setNumberValue("volume_to", $new)
                && $this->validateValueWithSibling('volume_from');

        if($res){
            $this->transportAddYourName(true);
            return ;
        }


        $this->hook->page->onlyEnterPage("transport_add_volume_to_btn", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT, false);
        $this->setAction("transportAddToVolume");
    }

    public function transportAddYourName($new = false){
        $this->back("transportAddToVolume", $new);

        if(!$new){
            if($this->hook->text){
                $this->setCache("fullname", $this->hook->text);
                return $this->transportAddYourPhone(true);
            }
        }

        $this->hook->page->enterName("enter_name_msg");
        $this->setAction("transportAddYourName");

    }

    public function transportAddYourPhone($new = false){
        $this->back("transportAddYourName", $new);
        if(!$new){
            if($phone = $this->getPhoneNumber()){

                $this->setCache("phone", $phone);
                return $this->confirm();
            }
        }
        $this->hook->page->enterPhone("enter_phone_msg");
        $this->setAction("transportAddYourPhone");
    }
    

    /*************************** transport add ********************************/

    /*************************** cargo add ********************************/

    public function cargoCountryFromAdd($new = false){
        $this->back("main", $new);

        if($new){
            $this->hook->page->sendMessage($this->hook->getLetter('add_transport_msg'));
            $this->hook->page->sendMessage($this->hook->getLetter("from_map_msg"));
        }

        $this->countryDefault("cargoRegionFromAdd","country_btn","from", $new, false);
        $this->setAction("cargoCountryFromAdd");
        return true;
    }

    public function cargoRegionFromAdd($new = false){
        $this->back("cargoCountryFromAdd", $new);

        $this->regionDefault("cargoCountryToAdd","region_btn","from", $new, false);
        $this->setAction("cargoRegionFromAdd");
    }

    public function cargoCountryToAdd($new = false){
        $this->back("cargoRegionFromAdd", $new);
        if($new){
            $this->hook->page->sendMessage($this->hook->getLetter("to_map_msg"));
        }
        $this->countryDefault("cargoRegionToAdd","country_btn","to", $new, false);
        $this->setAction("cargoCountryToAdd");
        return true;
    }

    public function cargoRegionToAdd($new = false){
        $this->back("cargoCountryToAdd", $new);

        $this->regionDefault("cargoAddStartDateAdd","region_btn","to", $new,  false);
        $this->setAction("cargoRegionToAdd");
        return true;
    }

    public function cargoAddStartDateAdd($new = false){
        if($new){
            $this->removeCache("offset_date");
        }
        $this->back("cargoRegionToAdd", $new);
        $page = $this->getPaginationValueForDate($new);

        if($this->setDate("start_date", $new)){

            return $this->cargoAddFinishDate(true);
        }

        $this->hook->page->date("cargo_date_start_msg",$page);
        $this->setCache("offset_date", $page);
        $this->setAction("cargoAddStartDateAdd");
    }

    public function cargoAddFinishDate($new = false){
        if($new){
            $this->removeCache("offset_date");
        }

        $this->back("cargoAddStartDateAdd", $new);
        $page = $this->getPaginationValueForDate($new);

        if($this->setDate("finish_date", $new) && $this->validateDateWithSibling("start_date")){
            return $this->cargoAddName(true);
        }

        $this->hook->page->date("cargo_date_finish_msg",$page);
        $this->setCache("offset_date", $page);
        $this->setAction("cargoAddFinishDate");
    }

    public function cargoAddName($new = false){
        $this->back("cargoAddStartDateAdd", $new);

        if(!$new){
            if($this->hook->text){
                $this->setCache("cargo_name", $this->hook->text);
                return $this->cargoAddWeight(true);
            }
        }

        $this->hook->page->onlyEnterPage("enter_cargo_name", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT, false);

        $this->setAction("cargoAddName");
    }

    public function cargoAddWeight($new = false){
        $this->back("transportAddFinishDate", $new);

        $res = $this->setNumberValue("cargo_weight", $new);

        if($res){
            $this->cargoAddVolume(true);
            return ;
        }

        $this->hook->page->onlyEnterPage("cargo_add_weight_btn", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT, false);
        $this->setAction("cargoAddWeight");
    }

    public function cargoAddVolume($new = false){
        $this->back("transportAddToWeight", $new);
        $res = $this->setNumberValue("cargo_volume", $new);

        if($res){
            $this->cargoTransportTypeAdd(true);
            return ;
        }

        $this->hook->page->onlyEnterPage("cargo_add_volume_btn", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT, false);
        $this->setAction("cargoAddVolume");
    }

    public function cargoTransportTypeAdd($new = false){
        $this->back("transportRegionToAdd", $new);

        $page = $this->getPaginationValue(TransportType::class, "transport_type", $new);

        if(!$new && $id = $this->transportID()){
            $this->setCache("transport_type",$id);
            $this->cargoAddYourName(true);
            return true;
        }

        $this->hook->page->transportType("cargo_choose_transport_type_msg", $page, false);
        $this->setAction("cargoTransportTypeAdd");
        return true;
    }

    public function cargoAddYourName($new = false){
        $this->back("cargoTransportTypeAdd", $new);

        if(!$new){
            if($this->hook->text){
                $this->setCache("fullname", $this->hook->text);
                return $this->cargoAddYourPhone(true);
            }
        }

        $this->hook->page->enterName("enter_name_msg");
        $this->setAction("cargoAddYourName");

    }

    public function cargoAddYourPhone($new = false){
        $this->back("cargoAddYourName", $new);

        if(!$new){

            if($phone = $this->getPhoneNumber()){
                $this->setCache("phone", $phone);

                return $this->confirm(true);
            }
        }
        $this->hook->page->enterPhone("enter_phone_msg");
        $this->setAction("cargoAddYourPhone");
    }

    /*************************** cargo add ********************************/

    /*************************** Profile ********************************/

    public function profile($new = false){

        if(!$new) {
            if ($this->hook->text == $this->hook->getLetter("my_cargo_btn")) {
                return $this->myCargo(true);
            }

            if ($this->hook->text == $this->hook->getLetter("my_transport_btn")) {
                return $this->myTransport(true);
            }
        }


        $text = $this->getMyStatistic();

        $this->hook->page->profile($text);


        $this->setAction("profile");
    }

    public function myCargo($new = false){

        if(!$new){
            if($this->hook->text == $this->hook->getLetter("to_remove_btn")){
                return $this->removeItem("cargo");
            }elseif($this->hook->text == $this->hook->getLetter("to_change_btn")){
                return $this->changeItem("cargo");
            }elseif($this->hook->text == $this->hook->getLetter("to_up_btn")){
                return $this->upItem("cargo");
            }elseif($this->hook->text == $this->hook->getLetter("next_btn")){
                $this->nextItem("cargo");
                return $this->myCargo(true);
            }elseif($this->hook->text == $this->hook->getLetter("prev_btn")){
                $this->prevItem("cargo");
                return $this->myCargo(true);
            }elseif($this->hook->text == $this->hook->getLetter("back_btn")){
                return $this->profile();
            }
        }

        $count_list = count(explode(",", $this->getCache("my_cargo_list")));

        if($count_list == 0){
            return $this->hook->page->sendMessage("no_cargo_msg");
        }

        $id = $this->getCache("current_cargo");
        if(!$id){
            $id = $this->getFirstItem("cargo");
            $this->setCache("current_cargo", $id);
        }


        $page = $this->getCache("current_cargo_page") + 1;
        $page_info = $this->hook->getLetter("paeg_info_msg");
        $page_info = str_replace(['{{current}}', "{{page}}"], [($page), $count_list], $page_info);

        $text = $page_info."\n".$this->getCargoItem($id);

        $this->hook->page->itemController($text, $count_list);
        $this->setAction("myCargo");
    }

    public function myTransport($new = false){


        if(!$new){
            if($this->hook->text == $this->hook->getLetter("to_remove_btn")){
                return $this->removeItem("transport");
            }elseif($this->hook->text == $this->hook->getLetter("to_up_btn")){
                return $this->upItem("transport");
            }elseif($this->hook->text == $this->hook->getLetter("to_change_btn")){
                return $this->changeItem("transport");
            }elseif($this->hook->text == $this->hook->getLetter("next_btn")){
                $this->nextItem("transport");
                return $this->myTransport(true);
            }elseif($this->hook->text == $this->hook->getLetter("prev_btn")){
                $this->prevItem("transport");
                return $this->myTransport(true);
            }elseif($this->hook->text == $this->hook->getLetter("back_btn")){
                return $this->profile();
            }
        }

        $count_list = count(explode(",", $this->getCache("my_transport_list")));

        if($count_list == 0){
            return $this->hook->page->sendMessage("no_transport_msg");
        }

        $id = $this->getCache("current_transport");
        if(!$id){
            $id = $this->getFirstItem("transport");
            $this->setCache("current_transport", $id);
        }


        $page = $this->getCache("current_transport_page") + 1;
        $page_info = $this->hook->getLetter("paeg_info_msg");
        $page_info = str_replace(['{{current}}', "{{page}}"], [($page), $count_list], $page_info);

        $text = $page_info."\n".$this->getTransportItem($id);

        $this->hook->page->itemController($text, $count_list);
        $this->setAction("myTransport");
    }


    public function removeItem($key){
        $class = "\common\models\\".ucfirst($key)."Info";
        $id = $this->getCache("current_".$key);
        $item = $class::findOne(['id'=> $id]);

        if($item){

            $item->delete();
            $this->hook->page->sendMessage($this->hook->getLetter("action_success_msg"));
        }

        return $this->profile(true);
    }

    public function upItem($key){
        $id = $this->getCache("current_".$key);

        \Yii::$app->db->createCommand(
            "update {$key}_info set created_at = '".date("Y-m-d H:i:s")."' where id=".$id
        )->execute();

        $this->hook->page->sendMessage($this->hook->getLetter("action_success_msg") . " ". date("Y-m-d H:i:s"));

        return $this->profile(true);
    }

    public function changeItem($key){
        $this->setCache("section", $key);
        return $this->{ucfirst($key)."CountryFromChange"}(true);
    }
    /*************************** Profile ********************************/

    /*************************** cargo profile change********************************/

    public function cargoCountryFromChange($new = false){


        $this->hook->page->sendMessage($this->hook->getLetter("from_map_msg"));
        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_country_msg");
        $country_name = ($map = $model->fromMap) ? $map->countryName : null;
        $this->setCache("country_from", $map->country_id);
        $this->setCache("old_country_from", $map->country_id);

        if($new){
            $text = str_replace("{{country}}", $country_name, $text);
            $this->hook->page->sendMessage($text);
        }

        $this->back("myCargo", $new);
        $this->skip("cargoRegionFromChange", $new);


        $this->countryDefault("cargoRegionFromChange","country_change_btn","from", $new);
        $this->setAction("cargoCountryFromChange");
        return true;
    }

    public function cargoRegionFromChange($new = false){

        $changed_country = $this->getCache("country_from") != $this->getCache("old_country_from");

        if($changed_country){

            $model = $this->getCurrentCargo();
            $text = $this->hook->getLetter("current_region_msg");
            $region_name = ($map = $model->fromMap) ? $map->regionName : null;
            $this->setCache("region_from", $map->region_id);
        }
        if($new){



            if(!$changed_country){
                $text = str_replace("{{region}}", $region_name, $text);
                $this->hook->page->sendMessage($text);
            }
        }

        $this->back("cargoCountryFromChange", $new);
        $this->skip("cargoCountryToChange", $new);

        $this->regionDefault("cargoCountryToChange","current_region_btn","from", $new, !$changed_country);
        $this->setAction("cargoRegionFromChange");
    }

    public function cargoCountryToChange($new = false){
        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_country_msg");
        $country_name = ($map = $model->toMap) ? $map->countryName : null;
        $this->setCache("country_to", $map->country_id);
        $this->setCache("old_country_to", $map->country_id);
        if($new){


            $text = str_replace("{{country}}", $country_name, $text);
            $this->hook->page->sendMessage($text);
        }

        $this->back("cargoRegionFromChange", $new);
        $this->skip("cargoRegionToChange", $new);

        $this->countryDefault("cargoRegionToChange","country_change_btn","to", $new);
        $this->setAction("cargoCountryToChange");
        return true;
    }

    public function cargoRegionToChange($new = false){
        $changed_country = $this->getCache("country_to") != $this->getCache("old_country_to");

        if($changed_country){

            $model = $this->getCurrentCargo();
            $text = $this->hook->getLetter("current_region_msg");
            $region_name = ($map = $model->fromMap) ? $map->regionName : null;
            $this->setCache("region_to", $map->region_id);
        }
        if($new){

            if(!$changed_country){

                $text = str_replace("{{region}}", $region_name, $text);
                $this->hook->page->sendMessage($text);
            }
        }
        $this->back("cargoCountryToChange", $new);
        $this->skip("cargoChangeStartDate", $new);

        $this->regionDefault("cargoChangeStartDate","current_region_btn","to", $new, !$changed_country);
        $this->setAction("cargoRegionToChange");
        return true;
    }

    public function cargoChangeStartDate($new = false){
        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_date_msg");
        $value = ($date = $model->date) ? $date->from : null;
        $this->setCache("start_date", $value);

        if($new){
            $this->removeCache("offset_date");

            $text = str_replace("{{date}}", $value, $text);
            $this->hook->page->sendMessage($text);
        }
        $page = $this->getPaginationValueForDate($new);

        if($this->setDate("start_date", $new)){

            return $this->cargoChangeFinishDate(true);
        }


        $this->back("cargoRegionToChange", $new);
        $this->skip("cargoChangeFinishDate", $new);

        $this->hook->page->date("cargo_date_start_msg",$page, true);
        $this->setCache("offset_date", $page);
        $this->setAction("cargoChangeStartDate");
    }

    public function cargoChangeFinishDate($new = false){
        if($new){
            $this->removeCache("offset_date");
        }

        $page = $this->getPaginationValueForDate($new);

        if($this->setDate("finish_date", $new) && $this->validateDateWithSibling("start_date")){
            return $this->cargoChangeName(true);
        }

        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_date_msg");
        $value = ($date = $model->date) ? $date->to : null;

        $this->setCache("finish_date", $value);

        $this->back("cargoChangeStartDate", $new);
        $this->skip("cargoChangeName", $new);

        $text = str_replace("{{date}}", $value, $text);
        $this->hook->page->sendMessage($text);

        $this->hook->page->date("cargo_date_finish_msg",$page, true);
        $this->setCache("offset_date", $page);
        $this->setAction("cargoChangeFinishDate");
    }

    public function cargoChangeName($new = false){
        $this->back("cargoChangeFinishDate", $new);
        $this->skip("cargoChangeWeight", $new);

        if(!$new){
            if($this->hook->text){
                $this->setCache("cargo_name", $this->hook->text);
                return $this->cargoChangeWeight(true);
            }
        }

        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_value_msg");
        $value = ($item = $model->cargo) ? $item->title : null;

        $this->setCache("cargo_name", $value);

        $text = str_replace("{{value}}", $value, $text);
        $this->hook->page->sendMessage($text);

        $this->hook->page->onlyEnterPage("enter_cargo_name", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT);

        $this->setAction("cargoChangeName");
    }

    public function cargoChangeWeight($new = false){
        $this->back("cargoChangeName", $new);
        $this->skip("cargoChangeVolume", $new);

        $res = $this->setNumberValue("cargo_weight", $new);

        if($res){
            $this->cargoChangeVolume(true);
            return ;
        }

        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_value_msg");
        $value = ($item = $model->cargo) ? $item->weight : null;

        $this->setCache("cargo_weight", $value);
        $text = str_replace("{{value}}", $value, $text);
        $this->hook->page->sendMessage($text);

        $this->hook->page->onlyEnterPage("cargo_add_weight_btn", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT);
        $this->setAction("cargoChangeWeight");
    }

    public function cargoChangeVolume($new = false){
        $this->back("cargoChangeWeight", $new);
        $this->skip("cargoTransportTypeChange", $new);
        $res = $this->setNumberValue("cargo_volume", $new);

        if($res){
            $this->cargoTransportTypeChange(true);
            return ;
        }

        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_value_msg");
        $value = ($item = $model->cargo) ? $item->volume : null;

        $this->setCache("cargo_volume", $value);

        $text = str_replace("{{value}}", $value, $text);
        $this->hook->page->sendMessage($text);

        $this->hook->page->onlyEnterPage("cargo_add_volume_btn", TelegramPage::ENTER_KEYBOARD_TYPE_DEFAULT);
        $this->setAction("cargoChangeVolume");
    }

    public function cargoTransportTypeChange($new = false){
        $this->back("cargoChangeVolume", $new);
        $this->skip("cargoChangeYourName", $new);

        $page = $this->getPaginationValue(TransportType::class, "transport_type", $new);

        if(!$new && $id = $this->transportID()){
            $this->setCache("transport_type",$id);
            $this->cargoAddYourName(true);
            return true;
        }

        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_value_msg");
        $value = ($item = $model->transportType) ? $item->{"name_".$this->hook->lang} : null;

        $this->setCache("transport_type", $item->id);
        $text = str_replace("{{value}}", $value, $text);
        $this->hook->page->sendMessage($text);

        $this->hook->page->transportType("cargo_choose_transport_type_msg", $page);
        $this->setAction("cargoTransportTypeChange");
        return true;
    }

    public function cargoChangeYourName($new = false){
        $this->back("cargoTransportTypeChange", $new);
        $this->skip("cargoChangeYourPhone", $new);

        if(!$new){
            if($this->hook->text){
                $this->setCache("fullname", $this->hook->text);
                return $this->cargoChangeYourPhone(true);
            }
        }

        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_value_msg");
        $value = ($item = $model->userInfo) ? $item->name : null;

        $this->setCache("fullname", $value);
        $text = str_replace("{{value}}", $value, $text);
        $this->hook->page->sendMessage($text);

        $this->hook->page->enterName("enter_name_msg", true);
        $this->setAction("cargoChangeYourName");

    }

    public function cargoChangeYourPhone($new = false){
        $this->back("cargoChangeYourName", $new);
        $this->skip("confirm", $new);

        if(!$new){

            if($phone = $this->getPhoneNumber()){
                $this->setCache("phone", $phone);

                return $this->confirm(true);
            }
        }

        $model = $this->getCurrentCargo();
        $text = $this->hook->getLetter("current_value_msg");
        $value = ($item = $model->userInfo) ? $item->phone : null;
        $this->setCache("phone", $value);
        $text = str_replace("{{value}}", $value, $text);
        $this->hook->page->sendMessage($text);

        $this->hook->page->enterPhone("enter_phone_msg", true);
        $this->setAction("cargoChangeYourPhone");
    }

    /*************************** cargo profile change********************************/

    /*************************** transport profile change********************************/
    public function transportCountryFromChange($new = false){
        $model = $this->getCurrentTransport();
        $text = $this->hook->getLetter("current_country_msg");
        $country_name = ($map = $model->fromMap) ? $map->countryName : null;
        $this->setCache("country_from", $map->country_id);
        $this->setCache("old_country_from", $map->country_id);
        $this->back("main", $new);
        $this->skip("transportRegionFromChange", $new);

        if($new){

            $text = str_replace("{{country}}", $country_name, $text);
            $this->hook->page->sendMessage($text);
            $this->hook->page->sendMessage($this->hook->getLetter("from_map_msg"));

        }

        $this->countryDefault("transportRegionFromChange","country_btn","from", $new);
        $this->setAction("transportCountryFromChange");
        return true;
    }

    public function transportRegionFromChange($new = false){
        $changed_country = $this->getCache("country_from") != $this->getCache("old_country_from");

        if($changed_country){

            $model = $this->getCurrentTransport();
            $text = $this->hook->getLetter("current_region_msg");
            $region_name = ($map = $model->fromMap) ? $map->regionName : null;
            $this->setCache("region_from", $map->region_id);
        }
        if($new){
            if(!$changed_country){
                $text = str_replace("{{region}}", $region_name, $text);
                $this->hook->page->sendMessage($text);
            }
        }

        $this->back("transportCountryFromChange", $new);
        $this->skip("transportCountryToChange", $new);

        $this->regionDefault("transportCountryToChange","region_btn","from", $new);
        $this->setAction("transportRegionFromChange");
        return true;
    }

    public function transportCountryToChange($new = false){


        $model = $this->getCurrentTransport();
        $text = $this->hook->getLetter("current_country_msg");
        $country_name = ($map = $model->toMap) ? $map->countryName : null;
        $this->setCache("country_to", $map->country_id);
        $this->setCache("old_country_to", $map->country_id);

        $this->back("transportRegionFromChange", $new);
        $this->skip("transportRegionToChange", $new);

        if($new){
            $text = str_replace("{{country}}", $country_name, $text);
            $this->hook->page->sendMessage($text);

            $this->hook->page->sendMessage($this->hook->getLetter("to_map_msg"));
        }

        $this->countryDefault("transportRegionToChange","country_btn","to", $new);
        $this->setAction("transportCountryToChange");
        return true;
    }

    public function transportRegionToChange($new = false){
        $changed_country = $this->getCache("country_to") != $this->getCache("old_country_to");

        if($changed_country){

            $model = $this->getCurrentTransport();
            $text = $this->hook->getLetter("current_region_msg");
            $region_name = ($map = $model->fromMap) ? $map->regionName : null;
            $this->setCache("region_to", $map->region_id);
        }
        if($new){

            if(!$changed_country){

                $text = str_replace("{{region}}", $region_name, $text);
                $this->hook->page->sendMessage($text);
            }
        }

        $this->back("transportCountryToChange", $new);
        $this->skip("transportTypeChange", $new);

        $this->regionDefault("transportTypeChange","region_btn","to", $new);
        $this->setAction("transportRegionToChange");
        return true;
    }

    public function transportTypeChange($new = false){
        $this->back("transportRegionToChange", $new);
        $this->back("transportChangeStartDate", $new);

        $page = $this->getPaginationValue(TransportType::class, "transport_type", $new);

        if(!$new && $id = $this->transportID()){
            $this->setCache("transport_type",$id);
            $this->transportChangeStartDate(true);
            return true;
        }

        $model = $this->getCurrentTransport();
        $text = $this->hook->getLetter("current_value_msg");
        $value = ($item = $model->transportType) ? $item->{"name_".$this->hook->lang} : null;

        $this->setCache("transport_type", $item->id);
        $text = str_replace("{{value}}", $value, $text);
        $this->hook->page->sendMessage($text);

        $this->hook->page->transportType("choose_transport_type_msg", $page);
        $this->setAction("transportTypeChange");
        return true;
    }

    public function transportChangeStartDate($new = false){
        $model = $this->getCurrentTransport();
        $text = $this->hook->getLetter("current_date_msg");
        $value = ($date = $model->date) ? $date->from : null;
        $this->setCache("start_date", $value);

        if($new){
            $this->removeCache("offset_date");

            $text = str_replace("{{date}}", $value, $text);
            $this->hook->page->sendMessage($text);

            $this->removeCache("offset_date");
        }

        $this->back("transportTypeChange", $new);
        $this->skip("transportChangeFinishDate", $new);
        $page = $this->getPaginationValueForDate($new);

        if($this->setDate("start_date", $new)){

            return $this->transportChangeFinishDate(true);
        }

        $this->hook->page->date("transport_date_start_msg",$page, true);
        $this->setCache("offset_date", $page);
        $this->setAction("transportChangeStartDate");
    }

    public function transportChangeFinishDate($new = false){

        $this->back("cargoChangeStartDate", $new);
        $this->skip("transportChangeFromWeight", $new);


        $page = $this->getPaginationValueForDate($new);

        if($this->setDate("finish_date", $new) && $this->validateDateWithSibling("start_date")){
            return $this->transportChangeFromWeight(true);
        }


        if($new){
            $model = $this->getCurrentTransport();

            $value = ($date = $model->date) ? $date->to : null;
            $this->removeCache("offset_date");
            $text = $this->hook->getLetter("current_date_msg");
            $text = str_replace("{{date}}", $value, $text);
            $this->hook->page->sendMessage($text);
            $this->setCache("finish_date", $value);
        }



        $this->hook->page->date("transport_date_finish_msg",$page, true);
        $this->setCache("offset_date", $page);
        $this->setAction("transportChangeFinishDate");
    }

    public function transportChangeFromWeight($new = false){
        $this->back("transportChangeFinishDate", $new);

        $res = $this->setNumberValue("weight_from", $new);

        if($res){
            $this->transportChangeToWeight(true);
            return ;
        }

        $model = $this->getCurrentTransport();

        $weight_interval = $model->weightInterval;
        if($weight_interval){

            $this->setCache("weight_from", $weight_interval->from);
            $text = $this->hook->getLetter("current_value_msg");
            $text = str_replace("{{value}}", $weight_interval->from, $text);
            $this->hook->page->sendMessage($text);

        }


        $this->hook->page->onlyEnterPage("transport_add_weight_from_btn");
        $this->setAction("transportChangeFromWeight");
    }

    public function transportChangeToWeight($new = false){
        $this->back("transportChangeFromWeight", $new);
        $this->skip("transportChangeFromVolume", $new);

        $res = $this->setNumberValue("weight_to", $new)
            && $this->validateValueWithSibling('weight_from');

        if($res){
            $this->transportChangeFromVolume(true);
            return ;
        }

        $model = $this->getCurrentTransport();

        $weight_interval = $model->weightInterval;

        if($weight_interval){
            $this->setCache("weight_to", $weight_interval->to);
            $text = $this->hook->getLetter("current_value_msg");
            $text = str_replace("{{value}}", $weight_interval->to, $text);
            $this->hook->page->sendMessage($text);
        }


        $this->hook->page->onlyEnterPage("transport_add_weight_to_btn");
        $this->setAction("transportChangeToWeight");
    }

    public function transportChangeFromVolume($new = false){
        $this->back("transportChangeToWeight", $new);
        $this->skip("transportChangeToVolume", $new);

        $res = $this->setNumberValue("volume_from", $new);

        if($res){
            $this->transportChangeToVolume(true);
            return ;
        }

        $model = $this->getCurrentTransport();

        $volume_interval = $model->volumeInterval;

        if($volume_interval){
            $this->setCache("volume_from", $volume_interval->from);
            $text = $this->hook->getLetter("current_value_msg");
            $text = str_replace("{{value}}", $volume_interval->from, $text);
            $this->hook->page->sendMessage($text);
        }


        $this->hook->page->onlyEnterPage("transport_add_volume_from_btn");
        $this->setAction("transportChangeFromVolume");
    }

    public function transportChangeToVolume($new = false){
        $this->back("transportChangeFromVolume", $new);
        $this->back("transportChangeYourName", $new);
        $res = $this->setNumberValue("volume_to", $new)
            && $this->validateValueWithSibling('volume_from');

        if($res){
            $this->transportChangeYourName(true);
            return ;
        }

        $model = $this->getCurrentTransport();

        $volume_interval = $model->volumeInterval;

        if($volume_interval){
            $this->setCache("volume_to", $volume_interval->to);
            $text = $this->hook->getLetter("current_value_msg");
            $text = str_replace("{{value}}", $volume_interval->to, $text);
            $this->hook->page->sendMessage($text);
        }


        $this->hook->page->onlyEnterPage("transport_add_volume_to_btn");
        $this->setAction("transportChangeToVolume");
    }

    public function transportChangeYourName($new = false){
        $this->back("transportChangeToVolume", $new);

        if(!$new){
            if($this->hook->text){
                $this->setCache("fullname", $this->hook->text);
                return $this->transportChangeYourPhone(true);
            }
        }

        $model = $this->getCurrentTransport();
        $text = $this->hook->getLetter("current_value_msg");
        $value = ($item = $model->userInfo) ? $item->name : null;

        $this->setCache("fullname", $value);
        $text = str_replace("{{value}}", $value, $text);
        $this->hook->page->sendMessage($text);

        $this->hook->page->enterName("enter_name_msg", true);
        $this->setAction("transportChangeYourName");

    }

    public function transportChangeYourPhone($new = false){
        $this->back("transportChangeYourName", $new);
        $this->skip("confirm", $new);
        if(!$new){
            if($phone = $this->getPhoneNumber()){

                $this->setCache("phone", $phone);
                return $this->confirm();
            }
        }

        $model = $this->getCurrentTransport();
        $text = $this->hook->getLetter("current_value_msg");
        $value = ($item = $model->userInfo) ? $item->phone : null;
        $this->setCache("phone", $value);
        $text = str_replace("{{value}}", $value, $text);
        $this->hook->page->sendMessage($text);


        $this->hook->page->enterPhone("enter_phone_msg", true);
        $this->setAction("transportChangeYourPhone");
    }

    /*************************** transport profile change ********************************/

    public function getSearchItemAction($new = false){
        $page = 1;
        $section = $this->getCache("section");

        $count = $this->{"getCountSearch".ucfirst($section)}();
        if($count == 0){
            return $this->hook->page->emptyResult("empty_result");
        }

        if(!$new){
            if($this->hook->text == $this->hook->getLetter("more_btn")){
                $page = $this->getCache("page");
                $page++;
            }

            if($this->hook->text == $this->hook->getLetter("main_btn")){
                $this->main(true);
                exit;
            }
        }

        list($text, $more_btn) = $this->sendItems($page);

        if($text){
            $this->setCache("page", $page);
            $this->hook->page->paginate($text, $more_btn);
        }else{
            $this->hook->page->emptyResult("end_result_msg");
        }

        $this->setAction("getSearchItemAction");
    }

    public function confirm($new = false){
        $methhod = "preview".ucfirst($this->getCache("section"));
        if(!$new){
            $this->cancelOperation();

            $this->confirmOperation();
        }

        $this->hook->page->confirm($this->$methhod());

        $this->setAction("confirm");
    }

    public function search($new = false){
        if(!$new){
            $this->cancelOperation();
            $this->searchOperation();
        }

        $section = $this->getCache("section");
        $text = $this->searchItem($section);
        $this->hook->page->search($text);

        $this->setAction("search");
    }

    public function cancelOperation(){
        if($this->hook->text == $this->hook->getLetter("cancel_btn")){
            $this->main();
            exit;
        }
    }

    public function searchOperation(){
        if($this->hook->text == $this->hook->getLetter("search_btn")){

            $this->getSearchItemAction(true);
            exit;
        }
    }

    public function confirmOperation(){
        if($this->hook->text != $this->hook->getLetter("confirm_btn")){
            return false;
        }

        $section = $this->getCache("section");

        $id = $this->getCache("current_".$section);

        $method = "add".ucfirst($section);
        $res = $this->$method($id);

        if($res){
            $this->hook->page->sendMessage($this->hook->getLetter($section."_success_msg"));
            $this->main(true);

        }else{
            $this->hook->page->sendMessage($this->hook->getLetter("error_in_add_msg"));
        }
        exit;
    }

    public function moreBtn(){
        if($this->hook->text == $this->hook->getLetter("cancel_btn")){
            $this->main();
            exit;
        }
    }

    public function sendItems($page){
        $section = $this->getCache("section");
        $items = $this->{"search".ucfirst($section)}($page);
        $method = "view".ucfirst($section);
        $count = count($items);
        $i=0;
        foreach($items as $key => $item){
            $i++;
            $text = $this->$method($item);

            if($count > ($key +1)){
                $this->hook->page->sendMessage($text);
            }

        }
        $more = true;
        if($i <= $this->hook->limit){
            $more = false;
        }
        return [$text, $more];
    }

    public function getCurrentCargo(){
        $id = $this->getCache("current_cargo");
        return CargoInfo::findOne($id);

    }

    public function getCurrentTransport(){
        $id = $this->getCache("current_transport");
        return \common\models\TransportInfo::findOne($id);

    }
}