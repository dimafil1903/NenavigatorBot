<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Telegram\Commands\truthoraction\Game;
use App\Telegram\Commands\truthoraction\StartGameKeyboard;
use App\Telegram\Commands\truthoraction\User;
use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */
class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.1.1';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {

        $callback_query = $this->getCallbackQuery();
        $callback_query_id = $callback_query->getId();

        $callback_data = $callback_query->getData();
        $message = $callback_query->getMessage();
        $message_id = $callback_query->getMessage()->getMessageId();
        $chat_id = $message->getChat()->getId();

        $chat = $message->getChat()->isGroupChat();


        $user = $callback_query->getFrom();
        $get_from = $callback_query->getFrom()->getId();
        $admins = Request::getChatMember(['chat_id' => "$chat_id", "user_id" => $get_from]);


        /////////////////
        /// /
        ///
        ///
        ///
        /// /////////////////

        $pieces = explode("_", $callback_data);


        if ($pieces[0] == 'joinAsMan' or $pieces[0] == 'joinAsGirl') {
            $forwardChatId = $pieces[1];
            $inline_keyboard = new StartGameKeyboard();
            $inline_keyboard = $inline_keyboard->getKeyboardLeave($forwardChatId);
            $data = [
                'chat_id' => "$chat_id",
                'message_id' => $message_id,
                'reply_markup' => $inline_keyboard,
                'text' => "Теперь ты учавствуешь в игре"
            ];


            $newUser = new User();
            $newUser->id = $user->getId();
            $newUser->first_name = $user->getFirstName();
            $newUser->username = $user->getUsername();
            if ($pieces[0] == "joinAsMan") {
                $newUser->gender = 'male';
            } else {
                $newUser->gender = 'female';
            }


            Game::addMemberToGame($newUser, $forwardChatId);
            Request::editMessageText($data);
        } elseif ($pieces[0] == 'leaveGame') {
            $data = [
                'chat_id' => "$chat_id",
                'message_id' => $message_id,

            ];
            $newUser = new User();
            $newUser->id = $user->getId();
            $newUser->first_name = $user->getFirstName();
            $newUser->username = $user->getUsername();
            $forwardChatId = $pieces[1];
            Game::LeaveFromGame($newUser, $forwardChatId);
            Request::deleteMessage($data);
        }
        ///////////
        ///
        ///
        ///
        ///
        ///
        /// /////

        $sender = "";
        $sender_with = "";
        foreach ($admins as $admin_key => $val) {
            if ($admin_key == 'result') {
                foreach ($val as $res => $value) {
                    if ($res == "user") {
                        foreach ($value as $user => $v) {
                            if ($user == "username") {
                                $sender = $v;
                                $sender_with = "@" . $v;
                            }
                            if ($sender == "") {
                                if ($user == "first_name") {
                                    $sender = $v;
                                    $sender_with = $sender;
                                }
                            }
                        }
                    }
                    if ($res == "status") {
                        if ($value == "administrator" || $value == "creator") {

                            if ($callback_data == 'back') {
                                $inline_keyboard = new InlineKeyboard([[
                                    ['text' => 'Задати максимальну кількість пар', 'callback_data' => 'max_count_of_par'],

                                ], [
                                    ['text' => 'Додати файл з розкладом', 'callback_data' => 'addtimetablefile'],
                                ],
                                    [
                                        ['text' => 'Налаштування дзвінків', 'callback_data' => 'calls_settings'],
                                        ['text' => 'Налаштування робочих днів', 'callback_data' => 'workdays_settings'],
                                    ]]);
                                $data_key = [
                                    'chat_id' => "$chat_id",
                                    'text' => 'Виберіть налаштування',
                                    'message_id' => $message_id,
                                    'reply_markup' => $inline_keyboard,
                                ];
                                Request::editMessageText($data_key);
                                Request::editMessageReplyMarkup($data_key);
                            }
                            if ($callback_data == 'addtimetablefile') {
                                $inline_keyboard = new InlineKeyboard([[
                                    ['text' => 'назад', 'callback_data' => 'back'],

                                ]]);
                                $data_key = [
                                    'chat_id' => "$chat_id",

                                    'message_id' => $message_id,
                                    'reply_markup' => $inline_keyboard,
                                    'text' => "Завантажте файл в наступному повідомленні",
                                    'inline_message_id' => $message_id
                                ];
                                Request::editMessageText($data_key);
                                Request::editMessageReplyMarkup($data_key);

                                $result = DB::table("messages")->
                                where("chat_id", "$chat_id")->
                                orderBy('id', 'desc')->
                                limit(1)->get();
                                foreach ($result as $id) {
                                    $id_last = $id->id;
                                }
                                $bot_msg_id = $id_last + 1;

                                // mysqli_close($link);


                                $bot_id = $this->getTelegram()->getBotId();
                                $date = date('Y-m-d H:i:s');
                                Request::sendMessage(['chat_id' => "$chat_id", 'text' => 'Завантажте файл з розкладом']);

                                DB::table('messages')->insert([
                                    'chat_id' => "$chat_id",
                                    'id' => $bot_msg_id,
                                    'user_id' => $bot_id,
                                    'text' => "Завантажте файл з розкладом",
                                    'date' => $date,

                                ]);

                            }
                            if ($callback_data == 'max_count_of_par') {

                                $inline_keyboard = new InlineKeyboard([
                                    [
                                        ['text' => '1', 'callback_data' => 'par1'],
                                        ['text' => '2', 'callback_data' => 'par2'],
                                    ],
                                    [
                                        ['text' => '3', 'callback_data' => 'par3'],
                                        ['text' => '4', 'callback_data' => 'par4'],
                                    ],
                                    [
                                        ['text' => '5', 'callback_data' => 'par5'],
                                        ['text' => '6', 'callback_data' => 'par6'],
                                    ],
                                    [
                                        ['text' => '7', 'callback_data' => 'par7'],
                                        ['text' => '8', 'callback_data' => 'par8'],
                                    ],
                                    [
                                        ['text' => 'назад', 'callback_data' => 'back'],
                                    ]]);
                                $data_key = [
                                    'chat_id' => "$chat_id",

                                    'message_id' => $message_id,
                                    'text' => 'Виберіть найбільшу кількість пар',
                                    'reply_markup' => $inline_keyboard,

                                ];
                                Request::editMessageText($data_key);
                                Request::editMessageReplyMarkup($data_key);
                            }


                            if ($callback_data == 'workdays_settings') {


                                $result = DB::table("leftsettings")->
                                where("chat_id", "$chat_id")->
                                get();

                                $json = "";
                                $txt = "При натисканні день змінює значення на 'вихідний', і навпаки.\n";
                                foreach ($result as $value) {

                                    for ($day = 1; $day <= 7; $day++) {
                                        $json .= ' "' . $day . '":"Робочий",';
                                    }
                                    if (empty($arr)) {


//                                            $query2 = "insert into leftsettings (chat_id,workday_settings) VALUES ('$chat_id','$json')";
//                                            mysqli_query($link, $query2) or die($text = 'ошибка');
                                        DB::table('leftsettings')->insert([
                                            'chat_id' => "$chat_id",
                                            'workday_settings' => $json,


                                        ]);
                                        $txt .= "Всі дні встановлені як робочі";
                                    } else if (!$value->workday_settings) {
//                                            $query2 = "UPDATE  leftsettings SET workday_settings='$json' WHERE chat_id = '$chat_id';";
//                                            mysqli_query($link, $query2) or die($text = 'ошибка');
                                        DB::table('leftsettings')->where('chat_id', "$chat_id")->update([
                                            'workday_settings' => $json,


                                        ]);
                                        $txt .= "Всі дні встановлені як робочі";
                                    } else {
                                        $workday_set = $value->workday_settings;
                                        $workday_set = substr($workday_set, 0, -1);
                                        $jsony = '{' . $workday_set . '}';
                                        $ary = json_decode($jsony, true);
                                        ksort($ary);
                                        foreach ($ary as $itemqy2 => $valueqy2) {

                                            switch ($itemqy2) {
                                                case "1":
                                                    $itemqy2 = "Понеділок - ";
                                                    break;
                                                case "2":
                                                    $itemqy2 = "Вівторок - ";
                                                    break;
                                                case "3":
                                                    $itemqy2 = "Середа - ";
                                                    break;
                                                case "4":
                                                    $itemqy2 = "Четвер - ";
                                                    break;
                                                case "5":
                                                    $itemqy2 = "П’ятниця - ";
                                                    break;
                                                case "6":
                                                    $itemqy2 = "Субота - ";
                                                    break;
                                                case "7":
                                                    $itemqy2 = "Неділя - ";
                                                    break;


                                            }
                                            $txt .= $itemqy2 . $valueqy2 . "\n";


                                        }
                                    }
                                }


                                $inline_keyboard = new InlineKeyboard([[
                                    ['text' => 'Понеділок', 'callback_data' => 'day1'],
                                    ['text' => 'Вівторок', 'callback_data' => 'day2'],
                                ],
                                    [
                                        ['text' => 'Середа', 'callback_data' => 'day3'],
                                        ['text' => 'Четвер', 'callback_data' => 'day4'],
                                    ],
                                    [
                                        ['text' => 'П’ятниця', 'callback_data' => 'day5'],
                                        ['text' => 'Субота', 'callback_data' => 'day6'],
                                    ],
                                    [
                                        ['text' => 'Неділя', 'callback_data' => 'day7'],
                                        ['text' => 'назад', 'callback_data' => 'back'],
                                    ]


                                ]);

                                $data_key = [
                                    'chat_id' => "$chat_id",

                                    'message_id' => $message_id,
                                    'text' => $txt,
                                    'reply_markup' => $inline_keyboard,

                                ];
                                Request::editMessageText($data_key);
                                Request::editMessageReplyMarkup($data_key);
                                //  mysqli_close($link);
                            }
                            for ($workday = 1; $workday <= 7; $workday++) {
                                if ($callback_data == 'day' . $workday) {
                                    $result = DB::table("leftsettings")->
                                    where("chat_id", "$chat_id")->
                                    get();
                                    $json = "";
                                    $txt = '';
                                    foreach ($result as $value) {
                                        $workday_set = $value->workday_settings;
                                        $workday_set = substr($workday_set, 0, -1);
                                        $jsony = '{' . $workday_set . '}';
                                        $ary = json_decode($jsony, true);
                                        ksort($ary);
                                        $js = "";
                                        foreach ($ary as $itemqy => $valueqy) {

                                            if ($itemqy == $workday and $valueqy == "Робочий") {
                                                $js .= "\"$workday" . "\":\"Вихідний\",";


                                            } else if ($itemqy == $workday and $valueqy == "Вихідний") {
                                                $js .= "\"$workday" . "\":\"Робочий\",";
                                            } else {
                                                $js .= "\"$itemqy\":\"$valueqy\",";
                                            }

                                            //   $query2 = "UPDATE  leftsettings SET workday_settings='$js' WHERE chat_id = '$chat_id';";
                                            DB::table('leftsettings')->where('chat_id', "$chat_id")->update([
                                                'workday_settings' => $js,


                                            ]);

                                            //  mysqli_query($link, $query2) or die($text = 'ошибка');

                                        }

                                        $result2 = DB::table("leftsettings")->
                                        where("chat_id", "$chat_id")->
                                        get();
                                        $txt = '';
                                        foreach ($result2 as $value5) {
                                            $workday_set = $value5->workday_settings;
                                            $workday_set = substr($workday_set, 0, -1);
                                            $jsony = '{' . $workday_set . '}';
                                            $ary = json_decode($jsony, true);
                                            ksort($ary);
                                            $js = "";
                                            foreach ($ary as $itemqy2 => $valueqy2) {

                                                switch ($itemqy2) {
                                                    case "1":
                                                        $itemqy2 = "Понеділок - ";
                                                        break;
                                                    case "2":
                                                        $itemqy2 = "Вівторок - ";
                                                        break;
                                                    case "3":
                                                        $itemqy2 = "Середа - ";
                                                        break;
                                                    case "4":
                                                        $itemqy2 = "Четвер - ";
                                                        break;
                                                    case "5":
                                                        $itemqy2 = "П’ятниця - ";
                                                        break;
                                                    case "6":
                                                        $itemqy2 = "Субота - ";
                                                        break;
                                                    case "7":
                                                        $itemqy2 = "Неділя - ";
                                                        break;


                                                }
                                                if ($valueqy2 == "Вихідний") {
                                                    $valueqy2 .= " 🥳";
                                                }
                                                $txt .= $itemqy2 . $valueqy2 . "\n";


                                            }
                                        }


                                    }


                                    $inline_keyboard = new InlineKeyboard([[
                                            ['text' => 'Понеділок', 'callback_data' => 'day1'],
                                            ['text' => 'Вівторок', 'callback_data' => 'day2'],
                                        ],
                                            [
                                                ['text' => 'Середа', 'callback_data' => 'day3'],
                                                ['text' => 'Четвер', 'callback_data' => 'day4'],
                                            ],
                                            [
                                                ['text' => 'П’ятниця', 'callback_data' => 'day5'],
                                                ['text' => 'Субота', 'callback_data' => 'day6'],
                                            ],
                                            [
                                                ['text' => 'Неділя', 'callback_data' => 'day7'],
                                                ['text' => 'назад', 'callback_data' => 'back'],
                                            ]

                                        ]
                                    );


                                    $data_key = [
                                        'chat_id' => "$chat_id",

                                        'message_id' => $message_id,
                                        'text' => "$txt",
                                        'reply_markup' => $inline_keyboard,

                                    ];
                                    Request::editMessageText($data_key);
                                    Request::editMessageReplyMarkup($data_key);


                                    //      mysqli_close($link);


                                }
                            }


                            if ($callback_data == 'clear_calls') {
//                                    $link = mysqli_connect('localhost', 'adminbot', '7C3h0J3l', 'nenavigator')
//                                    or die("Ошибка " . mysqli_error($link));
//                                    $query2 = "UPDATE  leftsettings SET calls_settings=NULL WHERE chat_id = '$chat_id';";
//                                    mysqli_query($link, $query2) or die($text = 'ошибка');
                                DB::table('leftsettings')->where('chat_id', "$chat_id")->update([
                                    'calls_settings' => null,


                                ]);
                                $data = [
                                    'chat_id' => "$chat_id",

                                    'message_id' => $message_id,
                                    'text' => "Дзвінки очищено",


                                ];
                                Request::sendMessage($data);
                                //   mysqli_close($link);
                            }


                            if ($callback_data == 'calls_settings') {


                                $inline_keyboard = new InlineKeyboard([[
                                    ['text' => 'Задати максимальну кількість пар', 'callback_data' => 'max_count_of_par'],

                                ], [
                                    ['text' => 'Додати файл з розкладом', 'callback_data' => 'addtimetablefile'],
                                ],
                                    [
                                        ['text' => 'Налаштування дзвінків', 'callback_data' => 'calls_settings'],
                                        ['text' => 'Налаштування робочих днів', 'callback_data' => 'workdays_settings'],
                                    ]]);


                                $result = DB::table("leftsettings")->
                                where("chat_id", "$chat_id")->
                                get();

                                if ($result->isNotEmpty()) {
                                    foreach ($result as $item) {
                                        if (!$item->max_par) {
                                            $data = ['chat_id' => "$chat_id", 'message_id' => $message_id, 'text' => "⛔️  Максимальна кільість пар не встановлена, будь ласка зробіть це ", 'reply_markup' => $inline_keyboard,];
                                            Request::editMessageText($data);
                                            Request::editMessageReplyMarkup($data);
                                        } else {
                                            $count = $item->max_par;
                                            $r = [];
                                            for ($i = 1; $i <= $count; $i++) {
                                                $subarr = ['text' => "$i", 'callback_data' => "call$i"];
                                                array_push($r, $subarr);
                                            }


                                            $selected_calls = DB::table("leftsettings")->
                                            where("chat_id", "$chat_id")->
                                            get();
                                            // $arr = mysqli_fetch_array($selected_calls);

                                            foreach ($selected_calls as $call) {

                                                $cal_set = $call->calls_settings;
                                                $json = '{' . $cal_set . '}';


                                                $ar = json_decode($json, true);
                                                ksort($ar);
                                                $txt = "";
                                                foreach ($ar as $itemq => $valueq) {
                                                    for ($i = 1; $i <= 8; $i++) {
                                                        if ($itemq == "$i" . "a") {
                                                            $txt .= "Пара №$i " . $valueq;
                                                        }
                                                        if ($itemq == "$i" . "z") {
                                                            $txt .= " - " . $valueq . "\n";
                                                        }
                                                    }

                                                }


                                            }
                                            $inline_keyboard = new InlineKeyboard([$r, [['text' => 'назад', 'callback_data' => 'back'], ['text' => 'Очистити дзвінки', 'callback_data' => 'clear_calls']]]);
                                            $data_key = [
                                                'chat_id' => "$chat_id",
                                                'text' => "Оберіть пару та задайте час ії початку і кінця\n" . $txt,
                                                'message_id' => $message_id,
                                                'reply_markup' => $inline_keyboard,
                                                'inline_message_id' => $message_id
                                            ];
                                            Request::editMessageText($data_key);
                                            Request::editMessageReplyMarkup($data_key);
                                        }
                                    }
                                    // mysqli_close($link);

                                } else {
                                    $data = ['chat_id' => "$chat_id", 'message_id' => $message_id, 'text' => "⛔️  Максимальна кільість пар не встановлена, будь ласка зробіть це ", 'reply_markup' => $inline_keyboard,];
                                    Request::editMessageText($data);
                                    Request::editMessageReplyMarkup($data);
                                }

                            }

                            for ($i = 1; $i <= 8; $i++) {
                                if ($callback_data == "par" . $i) {

                                    $result = DB::table("leftsettings")->
                                    where("chat_id", "$chat_id")->
                                    get();
                                    if ($result->isEmpty()) {

//                                            $query2 = "insert into leftsettings (chat_id,max_par) VALUES ('$chat_id','$i')";
//                                            mysqli_query($link, $query2) or die($text = 'ошибка');
                                        DB::table('leftsettings')->insert([
                                            'max_par' => $i,
                                            'chat_id' => "$chat_id",
                                        ]);
                                    } else {
//                                            $query2 = "UPDATE  leftsettings SET max_par='$i' WHERE chat_id = '$chat_id';";
//                                            mysqli_query($link, $query2) or die($text = 'ошибка');

                                        DB::table('leftsettings')->updateOrInsert(
                                            ['chat_id' => "$chat_id"],
                                            ['max_par' => $i]
                                        );
                                    }


                                    $data = ['chat_id' => "$chat_id", 'text' => "Максимальна кільість пар встановлена: " . $i];


                                    Request::sendMessage($data);


                                }

                            }
                            $resulty = DB::table("leftsettings")->
                            where("chat_id", "$chat_id")->
                            get();
                            $max_count = 0;
                            foreach ($resulty as $item) {
                                $max_count = $item->max_par;
                            }
                            for ($i = 1; $i <= $max_count; $i++) {
                                if ($callback_data == "call" . $i) {
                                    $t = "Введіть час ПОЧАТКУ пари №" . $i . "\n❗️  Вводити лише у форматі 00:00 ❗️\n✅   Наприклад:      08:00   ✅";


                                    $result = DB::table("messages")->
                                    where("chat_id", "$chat_id")->
                                    orderBy('id', 'desc')->
                                    limit(1)->get();
                                    foreach ($result as $id) {
                                        $id_last = $id->id;
                                    }
                                    $bot_msg_id = $id_last + 1;


                                    $bot_id = $this->getTelegram()->getBotId();
                                    $date = date('Y-m-d H:i:s');
                                    $data = ['chat_id' => "$chat_id", 'text' => $t];


                                    DB::table('messages')->insert([
                                        'chat_id' => $chat_id,
                                        'id' => $bot_msg_id,
                                        'user_id' => $bot_id,
                                        'text' => $t,
                                        'date' => $date,

                                    ]);


                                    Request::sendMessage($data);
                                }
                            }


                            //   mysqli_close($link);


                        } else {


                            if ($callback_data == 'back') {
                                $inline_keyboard = new InlineKeyboard([[
                                    ['text' => 'Задати максимальну кількість пар', 'callback_data' => 'max_count_of_par'],

                                ], [
                                    ['text' => 'Додати файл з розкладом', 'callback_data' => 'addtimetablefile'],
                                ],
                                    [
                                        ['text' => 'Налаштування дзвінків', 'callback_data' => 'calls_settings'],
                                        ['text' => 'Налаштування робочих днів', 'callback_data' => 'workdays_settings'],
                                    ]]);
                                $data_key = [
                                    'chat_id' => "$chat_id",
                                    'text' => 'Виберіть налаштування',
                                    'message_id' => $message_id,
                                    'reply_markup' => $inline_keyboard,
                                ];
                                Request::editMessageText($data_key);
                                Request::editMessageReplyMarkup($data_key);
                            }
                            if ($callback_data == 'addtimetablefile') {
                                $inline_keyboard = new InlineKeyboard([[
                                    ['text' => 'назад', 'callback_data' => 'back'],

                                ]]);
                                $data_key = [
                                    'chat_id' => "$chat_id",

                                    'message_id' => $message_id,
                                    'reply_markup' => $inline_keyboard,
                                    'text' => "Завантажте файл в наступному повідомленні",
                                    'inline_message_id' => $message_id
                                ];
                                Request::editMessageText($data_key);
                                Request::editMessageReplyMarkup($data_key);
                                $result = DB::table("messages")->
                                where("chat_id", "$chat_id")->
                                orderBy('id', 'desc')->
                                limit(1)->get();
                                foreach ($result as $id) {
                                    $id_last = $id->id;
                                }
                                $bot_msg_id = $id_last + 1;

                                //    mysqli_close($link);


                                $bot_id = $this->getTelegram()->getBotId();
                                $date = date('Y-m-d H:i:s');
                                Request::sendMessage(['chat_id' => "$chat_id", 'text' => 'Завантажте файл з розкладом']);

//                                    $query = "insert into message (chat_id,id,user_id,text,date) values ('$chat_id','$bot_msg_id','$bot_id','Завантажте файл з розкладом','$date')";
//                                    mysqli_query($link, $query) or die($text = 'ошибка');
                                DB::table('messages')->insert([
                                    'chat_id' => "$chat_id",
                                    'id' => $bot_msg_id,
                                    'user_id' => $bot_id,
                                    'text' => "Завантажте файл з розкладом",
                                    'date' => $date,

                                ]);


                            }
                            if ($callback_data == 'max_count_of_par') {

                                $inline_keyboard = new InlineKeyboard([
                                    [
                                        ['text' => '1', 'callback_data' => 'par1'],
                                        ['text' => '2', 'callback_data' => 'par2'],
                                    ],
                                    [
                                        ['text' => '3', 'callback_data' => 'par3'],
                                        ['text' => '4', 'callback_data' => 'par4'],
                                    ],
                                    [
                                        ['text' => '5', 'callback_data' => 'par5'],
                                        ['text' => '6', 'callback_data' => 'par6'],
                                    ],
                                    [
                                        ['text' => '7', 'callback_data' => 'par7'],
                                        ['text' => '8', 'callback_data' => 'par8'],
                                    ],
                                    [
                                        ['text' => 'назад', 'callback_data' => 'back'],
                                    ]]);
                                $data_key = [
                                    'chat_id' => "$chat_id",

                                    'message_id' => $message_id,
                                    'text' => 'Виберіть найбільшу кількість пар',
                                    'reply_markup' => $inline_keyboard,

                                ];
                                Request::editMessageText($data_key);
                                Request::editMessageReplyMarkup($data_key);
                            }


                            if ($callback_data == 'workdays_settings') {


                                $result = DB::table("leftsettings")->
                                where("chat_id", "$chat_id")->
                                get();
                                $json = "";
                                $txt = "При натисканні день змінює значення на 'вихідний', і навпаки.\n";
                                foreach ($result as $value) {

                                    for ($day = 1; $day <= 7; $day++) {
                                        $json .= ' "' . $day . '":"Робочий",';
                                    }
                                    if ($result->isEmpty()) {


//                                            $query2 ="insert into leftsettings (chat_id,workday_settings) VALUES ('$chat_id','$json')";
//                                            mysqli_query($link, $query2) or die($text='ошибка');
                                        DB::table('leftsettings')->insert([
                                            'workday_settings' => $json,
                                            'chat_id' => "$chat_id",

                                        ]);
                                        $txt .= "Всі дні встановлені як робочі";
                                    } else if (!$value->workday_settings) {
                                        DB::table('leftsettings')->updateOrInsert(
                                            ['chat_id' => "$chat_id"],
                                            ['workday_settings' => $json]);
                                        $txt .= "Всі дні встановлені як робочі";
                                    } else {
                                        $workday_set = $value->workday_settings;
                                        $workday_set = substr($workday_set, 0, -1);
                                        $jsony = '{' . $workday_set . '}';
                                        $ary = json_decode($jsony, true);
                                        ksort($ary);
                                        foreach ($ary as $itemqy2 => $valueqy2) {

                                            switch ($itemqy2) {
                                                case "1":
                                                    $itemqy2 = "Понеділок - ";
                                                    break;
                                                case "2":
                                                    $itemqy2 = "Вівторок - ";
                                                    break;
                                                case "3":
                                                    $itemqy2 = "Середа - ";
                                                    break;
                                                case "4":
                                                    $itemqy2 = "Четвер - ";
                                                    break;
                                                case "5":
                                                    $itemqy2 = "П’ятниця - ";
                                                    break;
                                                case "6":
                                                    $itemqy2 = "Субота - ";
                                                    break;
                                                case "7":
                                                    $itemqy2 = "Неділя - ";
                                                    break;


                                            }
                                            $txt .= $itemqy2 . $valueqy2 . "\n";


                                        }
                                    }
                                }


                                $inline_keyboard = new InlineKeyboard([[
                                    ['text' => 'Понеділок', 'callback_data' => 'day1'],
                                    ['text' => 'Вівторок', 'callback_data' => 'day2'],
                                ],
                                    [
                                        ['text' => 'Середа', 'callback_data' => 'day3'],
                                        ['text' => 'Четвер', 'callback_data' => 'day4'],
                                    ],
                                    [
                                        ['text' => 'П’ятниця', 'callback_data' => 'day5'],
                                        ['text' => 'Субота', 'callback_data' => 'day6'],
                                    ],
                                    [
                                        ['text' => 'Неділя', 'callback_data' => 'day7'],
                                        ['text' => 'назад', 'callback_data' => 'back'],
                                    ]


                                ]);

                                $data_key = [
                                    'chat_id' => "$chat_id",

                                    'message_id' => $message_id,
                                    'text' => $txt,
                                    'reply_markup' => $inline_keyboard,

                                ];
                                Request::editMessageText($data_key);
                                Request::editMessageReplyMarkup($data_key);
                                //  mysqli_close($link);
                            }
                            $json = "";
                            $txt = '';
                            for ($workday = 1; $workday <= 7; $workday++) {
                                if ($callback_data == 'day' . $workday) {

                                    $result = DB::table("leftsettings")->
                                    where("chat_id", "$chat_id")->
                                    get();

                                    foreach ($result as $value) {
                                        $workday_set = $value->workday_settings;
                                        $workday_set = substr($workday_set, 0, -1);
                                        $jsony = '{' . $workday_set . '}';
                                        $ary = json_decode($jsony, true);
                                        ksort($ary);
                                        $js = "";
                                        foreach ($ary as $itemqy => $valueqy) {

                                            if ($itemqy == $workday and $valueqy == "Робочий") {
                                                $js .= "\"$workday" . "\":\"Вихідний\",";


                                            } else if ($itemqy == $workday and $valueqy == "Вихідний") {
                                                $js .= "\"$workday" . "\":\"Робочий\",";
                                            } else {
                                                $js .= "\"$itemqy\":\"$valueqy\",";
                                            }


                                            DB::table('leftsettings')->updateOrInsert(
                                                ['chat_id' => "$chat_id"],
                                                ['workday_settings' => $js]);

                                            //    mysqli_query($link, $query2) or die($text='ошибка');

                                        }

                                        $result2 = DB::table("leftsettings")->
                                        where("chat_id", "$chat_id")->
                                        get();
                                        $txt = '';
                                        foreach ($result2 as $value5) {
                                            $workday_set = $value5->workday_settings;
                                            $workday_set = substr($workday_set, 0, -1);
                                            $jsony = '{' . $workday_set . '}';
                                            $ary = json_decode($jsony, true);
                                            ksort($ary);
                                            $js = "";
                                            foreach ($ary as $itemqy2 => $valueqy2) {

                                                switch ($itemqy2) {
                                                    case "1":
                                                        $itemqy2 = "Понеділок - ";
                                                        break;
                                                    case "2":
                                                        $itemqy2 = "Вівторок - ";
                                                        break;
                                                    case "3":
                                                        $itemqy2 = "Середа - ";
                                                        break;
                                                    case "4":
                                                        $itemqy2 = "Четвер - ";
                                                        break;
                                                    case "5":
                                                        $itemqy2 = "П’ятниця - ";
                                                        break;
                                                    case "6":
                                                        $itemqy2 = "Субота - ";
                                                        break;
                                                    case "7":
                                                        $itemqy2 = "Неділя - ";
                                                        break;


                                                }
                                                if ($valueqy2 == "Вихідний") {
                                                    $valueqy2 .= " 🥳";
                                                }
                                                $txt .= $itemqy2 . $valueqy2 . "\n";


                                            }
                                        }


                                    }


                                    $inline_keyboard = new InlineKeyboard([[
                                        ['text' => 'Понеділок', 'callback_data' => 'day1'],
                                        ['text' => 'Вівторок', 'callback_data' => 'day2'],
                                    ],
                                        [
                                            ['text' => 'Середа', 'callback_data' => 'day3'],
                                            ['text' => 'Четвер', 'callback_data' => 'day4'],
                                        ],
                                        [
                                            ['text' => 'П’ятниця', 'callback_data' => 'day5'],
                                            ['text' => 'Субота', 'callback_data' => 'day6'],
                                        ],
                                        [
                                            ['text' => 'Неділя', 'callback_data' => 'day7'],
                                            ['text' => 'назад', 'callback_data' => 'back'],
                                        ]


                                    ]);


                                    $data_key = [
                                        'chat_id' => "$chat_id",

                                        'message_id' => $message_id,
                                        'text' => "$txt",
                                        'reply_markup' => $inline_keyboard,

                                    ];
                                    Request::editMessageText($data_key);
                                    Request::editMessageReplyMarkup($data_key);


                                }
                            }


                            if ($callback_data == 'clear_calls') {

//                                    $query2 ="UPDATE  leftsettings SET calls_settings=NULL WHERE chat_id = '$chat_id';";
//                                    mysqli_query($link, $query2) or die($text='ошибка');

                                DB::table('leftsettings')->updateOrInsert(
                                    ['chat_id' => "$chat_id"],
                                    ['calls_settings' => null]);
                                $data = [
                                    'chat_id' => "$chat_id",

                                    'message_id' => $message_id,
                                    'text' => "Дзвінки очищено",


                                ];
                                Request::sendMessage($data);
                                //   mysqli_close($link);
                            }


                            if ($callback_data == 'calls_settings') {


                                $inline_keyboard = new InlineKeyboard([[
                                    ['text' => 'Задати максимальну кількість пар', 'callback_data' => 'max_count_of_par'],

                                ], [
                                    ['text' => 'Додати файл з розкладом', 'callback_data' => 'addtimetablefile'],
                                ],
                                    [
                                        ['text' => 'Налаштування дзвінків', 'callback_data' => 'calls_settings'],
                                        ['text' => 'Налаштування робочих днів', 'callback_data' => 'workdays_settings'],
                                    ]]);


                                $result = DB::table("leftsettings")->
                                where("chat_id", "$chat_id")->
                                get();

                                if ($result->isNotEmpty()) {
                                    foreach ($result as $item) {
                                        if (!$item->max_par) {
                                            $data = ['chat_id' => "$chat_id", 'message_id' => $message_id, 'text' => "⛔️  Максимальна кільість пар не встановлена, будь ласка зробіть це ", 'reply_markup' => $inline_keyboard,];
                                            Request::editMessageText($data);
                                            Request::editMessageReplyMarkup($data);
                                        } else {
                                            $count = $item->max_par;
                                            $r = [];
                                            for ($i = 1; $i <= $count; $i++) {
                                                $subarr = ['text' => "$i", 'callback_data' => "call$i"];
                                                array_push($r, $subarr);
                                            }


                                            $selected_calls = DB::table("leftsettings")->
                                            where("chat_id", "$chat_id")->
                                            get();

                                            foreach ($selected_calls as $call) {

                                                $cal_set = $call->calls_settings;
                                                $json = '{' . $cal_set . '}';


                                                $ar = json_decode($json, true);
                                                ksort($ar);
                                                $txt = "";
                                                foreach ($ar as $itemq => $valueq) {
                                                    for ($i = 1; $i <= 8; $i++) {
                                                        if ($itemq == "$i" . "a") {
                                                            $txt .= "Пара №$i " . $valueq;
                                                        }
                                                        if ($itemq == "$i" . "z") {
                                                            $txt .= " - " . $valueq;
                                                        }
                                                    }

                                                }
                                                $txt .= "\n";

                                            }
                                            $inline_keyboard = new InlineKeyboard([$r, [['text' => 'назад', 'callback_data' => 'back'], ['text' => 'Очистити дзвінки', 'callback_data' => 'clear_calls']]]);
                                            $data_key = [
                                                'chat_id' => "$chat_id",
                                                'text' => "Оберіть пару та задайте час ії початку і кінця\n" . $txt,
                                                'message_id' => $message_id,
                                                'reply_markup' => $inline_keyboard,
                                                'inline_message_id' => $message_id
                                            ];
                                            Request::editMessageText($data_key);
                                            Request::editMessageReplyMarkup($data_key);
                                        }
                                    }
                                    //  mysqli_close($link);

                                } else {
                                    $data = ['chat_id' => "$chat_id", 'message_id' => $message_id, 'text' => "⛔️  Максимальна кільість пар не встановлена, будь ласка зробіть це ", 'reply_markup' => $inline_keyboard,];
                                    Request::editMessageText($data);
                                    Request::editMessageReplyMarkup($data);
                                }

                            }

                            for ($i = 1; $i <= 8; $i++) {
                                if ($callback_data == "par" . $i) {

                                    $result = DB::table("leftsettings")->
                                    where("chat_id", "$chat_id")->
                                    get();
                                    if ($result->isEmpty()) {
                                        DB::table('leftsettings')->insert([
                                            'workday_settings' => $json,
                                            'max_par' => $i,
                                            "chat_id" => $chat_id
                                        ]);
                                        $txt .= "Всі дні встановлені як робочі";


//                                            $query2 ="insert into leftsettings (chat_id,max_par) VALUES ('$chat_id','$i')";
//                                            mysqli_query($link, $query2) or die($text='ошибка');
                                    } else {
//                                            $query2 ="UPDATE  leftsettings SET max_par='$i' WHERE chat_id = '$chat_id';";
//                                            mysqli_query($link, $query2) or die($text='ошибка');
                                        DB::table('leftsettings')->updateOrInsert(
                                            ['chat_id' => "$chat_id"],
                                            ['max_par' => $i]);
                                    }


                                    $data = ['chat_id' => "$chat_id", 'text' => "Максимальна кільість пар встановлена: " . $i];


                                    Request::sendMessage($data);


                                }

                            }
                            $resulty = DB::table("leftsettings")->
                            where("chat_id", "$chat_id")->
                            get();
                            $max_count = 0;
                            foreach ($resulty as $item) {
                                $max_count = $item->max_par;
                            }
                            for ($i = 1; $i <= $max_count; $i++) {
                                if ($callback_data == "call" . $i) {
                                    $t = "Введіть час ПОЧАТКУ пари №" . $i . "\n❗️  Вводити лише у форматі 00:00 ❗️\n✅   Наприклад:      08:00   ✅";


                                    $result = DB::table("messages")->
                                    where("chat_id", "$chat_id")->
                                    orderBy('id', 'desc')->
                                    limit(1)->get();
                                    foreach ($result as $id) {
                                        $id_last = $id->id;
                                    }
                                    $bot_msg_id = $id_last + 1;


                                    $bot_id = $this->getTelegram()->getBotId();
                                    $date = date('Y-m-d H:i:s');
                                    $data = ['chat_id' => "$chat_id", 'text' => $t];


                                    DB::table('messages')->insert([
                                        'chat_id' => "$chat_id",
                                        'id' => $bot_msg_id,
                                        'user_id' => $bot_id,
                                        'text' => $t,
                                        'date' => $date,

                                    ]);


                                    Request::sendMessage($data);
                                }
                            }


                            //     mysqli_close($link);
                        }

                    }


                }
            }


        }
    }
}
