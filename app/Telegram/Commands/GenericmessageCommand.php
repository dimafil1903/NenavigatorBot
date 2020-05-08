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


use App\Telegram\Commands\triggers\Trigger;
use App\Telegram\Commands\truthoraction\TruthOrAction;
use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 */
class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method if MySQL is required but not available
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function executeNoDb()
    {
        // Do nothing
        return Request::emptyResponse();
    }

    /**
     * Command execute method
     *
     * @return ServerResponse
     * @throws TelegramException
     */

    public function execute()
    {
        //If a conversation is busy, execute the conversation command after handling the message
        $conversation = new Conversation(
            $this->getMessage()->getFrom()->getId(),
            $this->getMessage()->getChat()->getId()
        );

        //Fetch conversation command if it exists and execute it
        if ($conversation->exists() && ($command = $conversation->getCommand())) {
            return $this->telegram->executeCommand($command);
        }
        $message = $this->getMessage();


        $chat_id = $message->getChat()->getId();

        $type = $message->getType();


        $is_left = false;


        $trigger = new Trigger($chat_id);
        $trigger->CreateTrigger($message);
        $trigger->CreateReTrigger($message);
        $trigger->getTrigger($message);

        $category = new TruthOrAction($chat_id);
        $category->CreateCategory($message);
        $category->CategoriesList($message);
        $category->createQuestion($message);
        if ($type == 'left_chat_member') {
            $left = $message->getLeftChatMember();
            $left_id = $left->getId();

            DB::table('user_chat')->
            where('chat_id', $chat_id)->where('user_id', $left_id)
                ->update(['is_left' => 1]);

            $data_left = [
                "chat_id" => $chat_id,
                "text" => "Пока, " . $left->getFirstName() . ""
            ];
            $is_left = true;

            Request::sendMessage($data_left);

        }
        if ($type == 'new_chat_members') {
            $members = $message->getNewChatMembers();
            $text = 'Привет всем!';

            if (!$message->botAddedInChat()) {
                $member_names = [];
                foreach ($members as $member) {
                    $member_id = $member->getId();

                    // выполняем операции с базой данных
                    //   $query ="UPDATE user_chat SET is_left=NULL WHERE user_id='$member_id' and chat_id='$chat_id'";
                    DB::table('user_chat')->
                    where('chat_id', $chat_id)->where('user_id', $member_id)
                        ->update(['is_left' => null]);


                    $member_names[] = $member->getFirstName();
                    $text = 'Привет, ' . implode(', ', $member_names) . '!';
                }
            }

            $data = [
                'chat_id' => $chat_id,
                'text' => $text,
            ];

            Request::sendMessage($data);

        }
        if ($is_left) {
            $data = ["chat_id" => $chat_id,
                'sticker' => "CAADAgADrwADTptkAoftMsdli6fnFgQ"
            ];

            Request::sendSticker($data);
        }
        if ($type == 'text') {
            $text = $message->getText();


            $dict = [
                'блядь', 'ебать', 'ебля', 'пизда', 'хуй', 'бля', 'блядина', 'блядский', 'блядское', 'блядская', 'блядство',
                'впиздячил', 'впиздячить', 'впиздячила', 'выблядок', 'выебон', 'выёбывается', 'выёбуются', 'выёбываться', 'выебуется', 'выебуются', 'выебываться', 'доебался',
                'доебалась', 'доебались', 'доебываться', 'доёбываться', 'ебало', 'ебанешься', 'ебанёшься', 'ебанул', 'ебанула', 'ебанулась', 'ебанулся', 'ебанулось',
                'ебашит', 'ебнул', 'ёбнул', 'заебал', 'заебала', 'заебали', 'заебать', 'заебись', 'заеб', 'заёб', 'уебался', 'уебалась',
                'наебнулся', 'наебнулась', 'наебнуться', 'пёзды', 'пезды', 'пизда', 'пиздабол', 'пиздатый', 'пиздатая', 'пиздатое', 'пиздец', 'подьебка',
                'подьёбка', 'подъёбка', 'подъебка', 'поебень', 'поебота', 'распиздяй', 'распиздяйка', 'спиздил', 'спиздила', 'спиздить', 'уебаться', 'уебище',
                'уёбище', 'хуево', 'хуёво', 'хуйня', 'шароебиться', 'шароёбиться', 'пздц', 'сука', 'ебал', 'долбаеб', 'долбаёб', 'хуёвая', 'хуевая', 'хуевый', 'хуёвый', 'ебаться', 'хуй', 'жопа', 'сучка', 'зараза', 'дохуя', 'пидор', 'ебобо', 'пидар', 'пидорас',
                'пидарас', 'педор', 'дебил'
            ];

            $answer = [
                'Не матюкайся пж!', 'Нащо так брудно мовиш?', 'Тобі не соромно?', 'Матюки - заборонені', 'Нахуй нада матюкаться?', 'Зрозуміло, бан', 'Ах ти ж матюклива зараза!!', 'УУУУ, Бог накаже за матюки!', 'Мамкі розкажу', 'А ти бабусі своїй таке скажи, вона не зрадіє', 'Будь ввічливіше, падла', 'А ти в курсі, що за такі слова можна піти в куток ?!', 'Які ми слова знаємо))', 'Помий рот з милом', 'ъуъ блін, може виправиш матюк ?', 'Я таких слів не терплю', 'Мені соромно за тебе', 'Хороша людина, а слова - то не твоє))))',

            ];


            $msg_id = $message->getMessageId();

            $check = false;
            $low_text = mb_strtolower($text);
            foreach ($dict as $item) {
                if ($item) {
                    if (strpos($low_text, $item) !== false) {
                        $check = true;
                    }
                }
            }

            if ($check) {
                $commandname = 'matpatrul';
                $arr = DB::table("matpatrul")->
                where('command', "$commandname")->
                where('chat_id', "$chat_id")->first();

                $rand_answer = $answer[mt_rand(0, count($answer) - 1)];
                // $arr=   (array)$arr;
                // dd($arr);
                if ($arr) {
                    if ($arr->is_active == 1) {
                        $data = [
                            'chat_id' => $chat_id,
                            'text' => "$rand_answer",
                            "reply_to_message_id" => $msg_id
                        ];

                        Request::sendMessage($data);
                    }
                }

            }


            $bot_id = $this->getTelegram()->getBotId();
            $msg_id = $message->getMessageId();
            $msg_text = $message->getText();

            for ($i = 1; $i <= 8; $i++) {
                $t = "Введіть час ПОЧАТКУ пари №" . $i . "\n❗️  Вводити лише у форматі 00:00 ❗️\n✅   Наприклад:      08:00   ✅";//Треба міняти ще раз в Callbackqurycommand.php


                $result = DB::table("messages")->
                where("chat_id", $chat_id)->
                where('text', $t)->
                where('user_id', $bot_id)->
                orderBy('id', 'desc')->
                limit(1)->get();

                if ($result) {
                    foreach ($result as $item) {
                        $true = false;

                        //  dd($result);
                        if ($item->id == $msg_id - 1 or $item->id == $msg_id - 2) {
                            $true = true;
                        }
                        if ($item->text == $t and $true == true) {


                            if (date('H:i', strtotime($message->getText())) == $message->getText()) {

                                $selected_calls = DB::table('leftsettings')->where('chat_id', $chat_id)->get();

                                foreach ($selected_calls as $call) {
                                    if (!$call->calls_settings) {

                                        $js = "\"$i" . "a\":\"$msg_text\"";
                                        DB::table('leftsettings')->
                                        where('chat_id', $chat_id)
                                            ->update(['calls_settings' => $js]);


                                    } else {
                                        $cal_set = $call->calls_settings;
                                        $json = '{' . $cal_set . '}';
                                        $ar = json_decode($json, true);
                                        $js = '';
                                        foreach ($ar as $cur_call => $time) {

                                            if ($cur_call != "$i" . "a") {
                                                $js .= "\"$cur_call\":\"$time\",";
                                            }

                                        }
                                        $js .= "\"$i" . "a\":\"$msg_text\"";
                                        // $query2 = "UPDATE  leftsettings SET calls_settings='$js' WHERE chat_id = '$chat_id';";
                                        DB::table('leftsettings')->
                                        where('chat_id', $chat_id)
                                            ->update(['calls_settings' => $js]);

                                        // mysqli_query($link, $query2) or die($text = 'ошибка');
                                    }
                                }

                                $t2 = "Введіть час КІНЦЯ пари №" . $i . "\n❗️  Вводити лише у форматі 00:00 ❗️\n✅   Наприклад:      09:20   ✅";
                                //     $query1 = "Select * FROM messages where chat_id='$chat_id' order BY id DESC LIMIT 1";

                                $result = DB::table("messages")->
                                where("chat_id", $chat_id)->
                                orderBy('id', 'desc')->
                                limit(1)->get();
                                //  $result = mysqli_query($link, $query1) or die($text = 'ошибка');
                                foreach ($result as $id) {
                                    $id_last = $id->id;
                                }
                                $bot_msg_id = $id_last + 1;
                                $bot_id = $this->getTelegram()->getBotId();
                                $date = date('Y-m-d H:i:s');
                                $data = ['chat_id' => $chat_id, 'text' => $t2];
                                //$query = "insert into message (chat_id,id,user_id,text,date) values ('$chat_id','$bot_msg_id','$bot_id','$t2','$date')";
                                //      mysqli_query($link, $query) or die($text = 'ошибка');
                                DB::table('messages')->insert([
                                    'chat_id' => $chat_id,
                                    'id' => $bot_msg_id,
                                    'user_id' => $bot_id,
                                    'text' => $t2,
                                    'date' => $date,

                                ]);
                                Request::sendMessage($data);

                            } else {
                                Request::sendMessage(['chat_id' => $chat_id,
                                    'text' => 'Помилка, спробуй ще раз']);
                            }
                        }
                    }
                }
                $txt2 = "Введіть час КІНЦЯ пари №" . $i . "\n❗️  Вводити лише у форматі 00:00 ❗️\n✅   Наприклад:      09:20   ✅";//Треба міняти ще раз вище

                //$query3 ="Select * FROM message where chat_id='$chat_id'and text='$txt2' and user_id='$bot_id' order BY id DESC LIMIT 1";
                //  $result2=mysqli_query($link, $query3) or die($text='ошибка');
                $result2 = DB::table("messages")->
                where("chat_id", $chat_id)->
                where("user_id", $bot_id)->
                where("text", $txt2)->
                orderBy('id', 'desc')->
                limit(1)->get();
                foreach ($result2 as $item) {
                    $true = false;


                    if ($item->id == $msg_id - 1 or $item->id == $msg_id - 2) {
                        $true = true;
                    }
                    if ($item->text == $txt2 and $true == true) {


                        if (date('H:i', strtotime($message->getText())) == $message->getText()) {

                            $selected_calls = DB::table('leftsettings')->where('chat_id', $chat_id)->get();

                            //  $arr=mysqli_fetch_array($selected_calls);
                            if ($selected_calls->isEmpty()) {
                                //   $query2 ="insert into leftsettings (chat_id,calls_settings) VALUES ('$chat_id','$i')";
                                // mysqli_query($link, $query2) or die($text='ошибка');
                                DB::table('leftsettings')->insert(
                                    [
                                        'chat_id' => $chat_id,
                                        'calls_settings' => $i,
                                    ]
                                );
                            } else {
                                foreach ($selected_calls as $call) {
                                    if (!$call->calls_settings) {

                                        $js = "\"$i" . "z\":\"$msg_text\"";

                                        DB::table('leftsettings')->
                                        where('chat_id', $chat_id)
                                            ->update(['calls_settings' => $js]);

                                    } else {
                                        $cal_set = $call->calls_settings;
                                        $json = '{' . $cal_set . '}';
                                        $ar = json_decode($json, true);
                                        $js = '';
                                        foreach ($ar as $cur_call => $time) {

                                            if ($cur_call != "$i" . "z") {
                                                $js .= "\"$cur_call\":\"$time\",";
                                            }

                                        }
                                        $js .= "\"$i" . "z\":\"$msg_text\"";
                                        DB::table('leftsettings')->
                                        where('chat_id', $chat_id)
                                            ->update(['calls_settings' => $js]);


                                        //    mysqli_query($link, $query2) or die($text='ошибка');
                                    }
                                }
                            }
                            Request::sendMessage(['chat_id' => $chat_id,
                                'text' => 'Добре, час пари №' . $i . ' встановлений']);

                        } else {
                            Request::sendMessage(['chat_id' => $chat_id,
                                'text' => 'Помилка, спробуй ще раз']);
                        }
                    }
                }


            }


            //  mysqli_close($link);
            Request::sendMessage(
                [
                    'chat_id' => '481629579',
                    'text' => $message->getChat()->getTitle() . "\n" .
                        $message->getFrom()->getFirstName() . ' ' . $message->getFrom()->getLastName() . "\n" .
                        "" . $message->getText()

                ]
            );
        } else if ($type == "document") {
            $msg_id = $message->getMessageId();
            $bot_id = $this->getTelegram()->getBotId();
            $doc = $message->getDocument();
            $data = [
                'chat_id' => "$chat_id",
            ];
            $file_id = $doc->getFileId();
            $data['document'] = $file_id;
            $response2 = Request::getFile(['file_id' => $file_id]);
//            $link = mysqli_connect('localhost', 'adminbot', '7C3h0J3l', 'nenavigator')
//            or die("Ошибка " . mysqli_error($link));
            //   mysqli_set_charset($link, 'utf8mb4');
//            $query ="Select * FROM message where chat_id='$chat_id'and user_id='$bot_id' order BY id DESC LIMIT 1";
//            $result=mysqli_query($link, $query) or die($text='ошибка');
            $result = DB::table('messages')->
            where('chat_id', "$chat_id")->
            where('user_id', $bot_id)->
            orderBy('id', 'desc')->
            limit(1)->
            get();
            $true = false;
            foreach ($result as $item) {
                if ($item->id == $msg_id - 2 or $item->id == $msg_id - 1) {
                    $true = true;
                }
                if ($item->text == 'Завантажте файл з розкладом' && $true == true) {
                    $t = $item->text;
                    //Download the photo after send message response to speedup response

                    if ($response2->isOk()) {

                        $photo_file = $response2->getResult();
                        $file_path = $photo_file->getFilePath();
                        $result = DB::table('leftsettings')->
                        where('chat_id', "$chat_id")->
                        get();

                        if ($result->isEmpty()) {
                            DB::table('leftsettings')->insert([
                                'file_path' => $file_path,
                                'chat_id' => "$chat_id",

                            ]);

                        } else {
                            DB::table('leftsettings')->updateOrInsert(
                                ['chat_id' => "$chat_id"],
                                ['file_path' => $file_path]
                            );
                        }
                        Request::downloadFile($photo_file);
                        Request::sendMessage(['chat_id' => "$chat_id", 'text' => 'завантаження успішне']);


                    }
                }


            }
            //   mysqli_close($link);
        } elseif ($type == 'photo') {

            $photo = $message->getPhoto();

            Request::sendPhoto(
                [
                    'chat_id' => '481629579',
                    'caption' => $message->getChat()->getTitle() . "\n" .
                        $message->getFrom()->getFirstName() . ' ' . $message->getFrom()->getLastName() . "\n" .
                        "" . $message->getCaption(),
                    'photo' => $photo[0]->getFileId()

                ]
            );
        }


    }
}
