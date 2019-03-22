<?php
namespace frontend\components\telegram;

use common\models\map\Country;
use common\models\map\Region;
use common\models\TransportType;

class TelegramPage{

    const ENTER_KEYBOARD_TYPE_SEARCH = "search";
    const ENTER_KEYBOARD_TYPE_ADD = "add";
    const ENTER_KEYBOARD_TYPE_DEFAULT = "skip";
    public $hook;
    public $lang;

    public function __construct(TelegramHook $telegram)
    {
        $this->hook = $telegram;
    }

    public function main($msg = null)
    {
        $keyboard =
                [
                    [
                        ['text' => $this->hook->getLetter("main_search_cargo_btn")],
                        ['text' => $this->hook->getLetter("main_search_transport_btn")]
                    ],
                    [
                        ['text' => $this->hook->getLetter("add_cargo_btn")],
                        ['text' =>  $this->hook->getLetter("add_transport_btn")],
                    ],
                    [
                        ['text' => $this->hook->getLetter("my_profile_btn")],
                        ['text' => $this->hook->getLetter("lang_btn")]
                    ]
        ];


        $msg = $this->hook->getLetter('main_msg');
        $this->sendMessage($msg, $keyboard);
    }

    public function lang(){
        $list = Lang::getList();
        $langs = [];

        foreach($list as $key => $name){
            $langs[] = ['text' => $name];
        }

        $keyboard =
                [
                    $langs,
                    [['text' => $this->hook->getLetter('back_btn')]],
                ];


        $msg = $this->hook->getLetter('choose_lang_msg');
        $this->sendMessage($msg, $keyboard);
    }

    public function country($msg_key, $page = 1, $skip = true){
        $keyboard = $this->getMapItemKeyboard(Country::class, [], $page, $skip, "country");
        $keyboard[] = [
            ['text' => $this->hook->getLetter('main_btn')],
        ];
        return $this->sendMessage($this->hook->getLetter($msg_key), $keyboard);
    }

    public function region($msg_key,$condition, $page = 1, $skip = true){
        $keyboard = $this->getMapItemKeyboard(Region::class, $condition, $page, $skip, "region");
        $keyboard[] = [
            ['text' => $this->hook->getLetter('main_btn')],
        ];
        return $this->sendMessage($this->hook->getLetter($msg_key), $keyboard);
    }

    public function transportType($msg_key, $page = 1, $skip = true){
        $name = "name_".$this->hook->lang;
        $count = TransportType::find()->select("count(*) count")->asArray()->one()['count'];
        $items = TransportType::find()->select($name)
                                    ->orderBy($name)->limit($this->hook->limit)
                                    ->offset(($page - 1) * $this->hook->limit)
                                    ->column();

        $keyboard = [];
        foreach($items as $name){
            $keyboard[] = [['text' => $name]];
        }

        if($count > $this->hook->limit){
            $keyboard[] =
                [
                    ['text' => $this->hook->getLetter('prev_btn')],
                    ['text' => $this->hook->getLetter('next_btn')],
                ]
            ;
        }

        if(!$skip){
            $keyboard[] =
                [
                    ['text' => $this->hook->getLetter('back_btn')],
                ];
        }else{
            $keyboard[] =
                [
                    ['text' => $this->hook->getLetter('back_btn')],
                    ['text' => $this->hook->getLetter('skip_btn')],
                ];
        }

        $keyboard[] = [
            ['text' => $this->hook->getLetter('main_btn')],
        ];
        return $this->sendMessage($this->hook->getLetter($msg_key), $keyboard);

    }

    public function onlyEnterPage($msg_key, $type = "skip", $skip = true){

        if($type == self::ENTER_KEYBOARD_TYPE_DEFAULT){
            if($skip){
                $keyboard[] =
                    [
                        ['text' => $this->hook->getLetter('back_btn')],
                        ['text' => $this->hook->getLetter($type.'_btn')],
                    ];
            }else{
                $keyboard[] =
                    [
                        ['text' => $this->hook->getLetter('back_btn')],
                    ];
            }

        }else{

            $keyboard[] =
                [
                    ['text' => $this->hook->getLetter('back_btn')],
                    ['text' => $this->hook->getLetter($type.'_btn')],
                ];
        }

        $keyboard[] = [
            ['text' => $this->hook->getLetter('main_btn')],
        ];

        return $this->sendMessage($this->hook->getLetter($msg_key), $keyboard);
    }

    public function enterName($msg_key, $skip = false){
        $keyboard = [];
        if($this->hook->fullname){
            $keyboard[] = [
                ['text' => $this->hook->fullname],
            ];
        }

        $inner_keyboard[] = ['text' => $this->hook->getLetter('back_btn')];

        if($skip){
            $inner_keyboard[] = ['text' => $this->hook->getLetter('skip_btn')];
        }

        $keyboard[] = $inner_keyboard;

        $keyboard[] = [
            ['text' => $this->hook->getLetter('main_btn')],
        ];

        return $this->sendMessage($this->hook->getLetter($msg_key), $keyboard);
    }

    public function enterPhone($msg_key, $skip = false){
        $keyboard = [
            [
                ['text' => $this->hook->getLetter("phone_btn"),'request_contact'=>true],
            ]
        ];

        $inner_keyboard[] = ['text' => $this->hook->getLetter('back_btn')];

        if($skip){
            $inner_keyboard[] = ['text' => $this->hook->getLetter('skip_btn')];
        }

        $keyboard[] = $inner_keyboard;

        $keyboard[] = [
            ['text' => $this->hook->getLetter('main_btn')],
        ];
        return $this->sendMessage($this->hook->getLetter($msg_key), $keyboard);
    }

    public function getMapItemKeyboard($class,$condition, $page = 1, $skip = true, $type){
        $page = $page > 0 ? $page : 1;

        $keyboard = [];
        $query = $class::find()->select('name');

        if($this->hook->action->getCache("type") == "search"){
            $query->join("join","map", $type."_id = {$class::tableName()}.id");
        }

        if($condition){
            $query->where($condition)->groupBy($class::tableName().'.id');
        }

        $count = $query->count();
        $items = $query->orderBy('position asc, name asc')
                                ->groupBy($class::tableName().'.id')
                                ->limit($this->hook->limit)
                                ->offset(($page - 1) * $this->hook->limit)
                                ->column();

        foreach($items as $name){
            $keyboard[] = [['text' => $name]];
        }

        if($count > $this->hook->limit){
            $keyboard[] =
                [
                    ['text' => $this->hook->getLetter('prev_btn')],
                    ['text' => $this->hook->getLetter('next_btn')],

                ]
            ;
        }

        if($skip){
            $keyboard[] =
                [
                    ['text' => $this->hook->getLetter('back_btn')],
                    ['text' => $this->hook->getLetter('skip_btn')],
                ];
        }else{
            $keyboard[] =
                [
                    ['text' => $this->hook->getLetter('back_btn')],
                ];
        }


        return $keyboard;
    }

    public function confirm($text){
        $keyboard = [
            [
                ['text' => $this->hook->getLetter('cancel_btn')],
                ['text' => $this->hook->getLetter('confirm_btn')],
            ]
        ];
        return $this->sendMessage($text, $keyboard);
    }

    public function search($text){
        $keyboard = [
            [
                ['text' => $this->hook->getLetter('cancel_btn')],
                ['text' => $this->hook->getLetter('search_btn')],
            ]
        ];
        return $this->sendMessage($text, $keyboard);
    }

    public function date($msg_key, $page = 1, $skip = false){
        $keyboard = $this->generateDateList($page);
        $keyboard[] =
            [
                ['text' => $this->hook->getLetter('prev_btn')],
                ['text' => $this->hook->getLetter('next_btn')],
            ]
        ;
        $inner_keyboard[] = ['text' => $this->hook->getLetter('back_btn')];

        if($skip){
            $inner_keyboard[] = ['text' => $this->hook->getLetter('skip_btn')];
        }

        $keyboard[] = $inner_keyboard;

        $keyboard[] = [
            ['text' => $this->hook->getLetter('main_btn')],
        ];
        return $this->sendMessage($this->hook->getLetter($msg_key), $keyboard);
    }

    public function generateDateList($page){
        $page = $page > 1 ? $page : 1;
        $keyboard = [];
        $start = ($page-1)*$this->hook->limit;
        $stop = $page* $this->hook->limit;
        for($i=$start; $i<=$stop; $i++){
            $keyboard[] = [['text' => date("d-M-Y", time()+3600*24*$i)]];
        }

        return $keyboard;
    }

    public function paginate($text, $more_btn = true){
        if($more_btn) {
            $keyboard = [
                [
                    ['text' => $this->hook->getLetter('more_btn')],
                ],
            ];
        }

        $keyboard[] =  [
            ['text' => $this->hook->getLetter('main_btn')],
        ];

        $this->sendMessage($text, $keyboard);
    }

    public function emptyResult($msg_key){
        $keyboard = [

            [
                ['text' => $this->hook->getLetter('main_btn')],
            ]
        ];

        $this->sendMessage($this->hook->getLetter($msg_key), $keyboard);
    }

    public function profile($text){
        $keyboard = [
            [
                ['text' => $this->hook->getLetter('my_cargo_btn')],
                ['text' => $this->hook->getLetter('my_transport_btn')],
            ],[
                ['text' => $this->hook->getLetter('main_btn')],
            ]
        ];

        $this->sendMessage($text, $keyboard);
    }

    public function itemController($text, $count){
        $keyboard = [
            [
                ['text' => $this->hook->getLetter("to_up_btn")],
                ['text' => $this->hook->getLetter("to_change_btn")],
                ['text' => $this->hook->getLetter("to_remove_btn")],
            ]
        ];

        if($count > 0){
            $keyboard[] =  [
                ['text' => $this->hook->getLetter('prev_btn')],
                ['text' => $this->hook->getLetter('next_btn')],
            ];
        }

        $keyboard[] =
            [
                ['text' => $this->hook->getLetter('back_btn')],
            ];

        return $this->sendMessage($text, $keyboard);

    }
    public function sendMessage($message, $keyboard = null, $inline = false){

        $options =  [
            'chat_id' => $this->hook->chat_id,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        if($keyboard){
            $key_type = "keyboard";
            if($inline){
                $key_type = "inline_keyboard";
            }
            $reply_markup = [
                $key_type => $keyboard
            ];
            $reply_markup['one_time_keyboard'] = true;
            $reply_markup['resize_keyboard'] = true;

            $options['reply_markup'] = json_encode($reply_markup, true);
        }


        return $this->hook->telegram->sendMessage($options
           );
    }

    public function sendMessageMultKeyboard($message, $reply_markup = null){

        $options =  [
            'chat_id' => $this->hook->chat_id,
            'text' => $message,
        ];

        if($reply_markup){

            $reply_markup['one_time_keyboard'] = true;
            $reply_markup['resize_keyboard'] = true;

            $options['reply_markup'] = json_encode($reply_markup, true);
        }


        return $this->hook->telegram->sendMessage($options
        );
    }

}