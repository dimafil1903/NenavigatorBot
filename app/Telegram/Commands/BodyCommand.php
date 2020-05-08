<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;


class BodyCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'body';

    /**
     * @var string
     */
    protected $description = 'хто король лохів на день?';

    /**
     * @var string
     */
    protected $usage = '/body';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();


        // выполняем операции с базой данных
        // $query ="Select * FROM user_chat LEFT JOIN user on user_chat.user_id=user.id WHERE user_chat.chat_id='$chat_id' and user_chat.is_left is NULL";
        $result = DB::table('user_chat')->
        leftJoin('telegram_users', 'user_chat.user_id', '=', 'telegram_users.id')->
        where('user_chat.chat_id', "$chat_id")->where('user_chat.is_left', null)->get();
        $current_members = [];
        foreach ($result as $member) {
            if ($member->is_bot == 0) {
                $name = $member->username;
                $name_with = "@" . $name;
                if (!$name) {
                    $name = $member->first_name;
                    $name_with = $name;
                }
                array_push($current_members, $name_with);
            }
        }
        $rand_member = $current_members[mt_rand(0, count($current_members) - 1)];
        if ($current_members == 0) {
            $rand_member = "одни боты";
        }

        $current = date('Y-m-d H:i:s');
        $next_day = date('Y-m-d 00:00:00', strtotime('+1 day'));


        //   $query ="Select * FROM usestime where command='body' and chat_id='$chat_id'";
        $result = DB::table('usestime')->where('command', 'body')->where('chat_id', "$chat_id")->first();
        //     $arr=mysqli_fetch_array($result);
        if (!$result) {
            //  $query ="insert into usestime (time_use,next_time_use,who,chat_id,command) VALUES ('$current','$next_day','$rand_member','$chat_id','body');";
            DB::table('usestime')->insert([
                'time_use' => $current,
                'next_time_use' => $next_day,
                'who' => $rand_member,
                'chat_id' => "$chat_id",
                'command' => 'body'
            ]);
            Request::sendChatAction([
                'chat_id' => "$chat_id",
                'action' => 'typing'
            ]);
            sleep(2);
            $data = [
                'chat_id' => $chat_id,
            ];
            $data['text'] = "Хммммм.....";
            Request::sendMessage($data);
            Request::sendChatAction([
                'chat_id' => "$chat_id",
                'action' => 'typing'
            ]);
            sleep(2);
            $data['text'] = "Зараз знайду Лоха.....";
            Request::sendMessage($data);
            sleep(2);
            $data = [
                'chat_id' => $chat_id,
                'text' => $rand_member . " - король лохів. Сьогодні він(вона) отримує по щелбану від кожного "
            ];
            Request::sendMessage($data);
        } else {
            if ((strtotime($current) >= strtotime($result->next_time_use))) {


                Request::sendChatAction([
                    'chat_id' => "$chat_id",
                    'action' => 'typing'
                ]);
                sleep(2);
                $data = [
                    'chat_id' => $chat_id,
                ];
                $data['text'] = "Хммммм.....";
                Request::sendMessage($data);
                Request::sendChatAction([
                    'chat_id' => "$chat_id",
                    'action' => 'typing'
                ]);
                sleep(2);
                $data['text'] = "Зараз знайду Лоха.....";
                Request::sendMessage($data);
                sleep(2);

                $data = [
                    'chat_id' => $chat_id,
                    'text' => $rand_member . " - король лохів. Сьогодні він(вона) отримує по щелбану від кожного"
                ];
                //   $query ="UPDATE usestime SET time_use='$current',next_time_use='$next_day',who='$rand_member' WHERE command='body' and chat_id='$chat_id'";
                //   mysqli_query($link, $query) or die($text='ошибка');
                DB::table('usestime')->where('chat_id', "$chat_id")->where('command', 'body')->
                update([
                    'time_use' => $current,
                    'next_time_use' => $next_day,
                    'who' => $rand_member,

                ]);
                Request::sendMessage($data);
            } else if ((strtotime($current) <= strtotime($result->next_time_use))) {

                $mod_date = strtotime($result->next_time_use) - (strtotime($current) - strtotime("00:00:00"));
                $l = date("H:i:s", $mod_date) . "\n";
                $data = [
                    'chat_id' => $chat_id,
                    'text' => "Притормози коней, до наступного разу " . $l . "А поки лох - " . str_replace('@', '', $result->who)
                ];
                Request::sendMessage($data);
            }
        }


        //   mysqli_close($link);


    }
}
