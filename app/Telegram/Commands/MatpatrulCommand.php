<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;


class MatpatrulCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'matpatrul';

    /**
     * @var string
     */
    protected $description = 'увімкнути мат-патруль';

    /**
     * @var string
     */
    protected $usage = '/matpatrul';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText();
        $commandname = $this->name;


        // выполняем операции с базой данных


        //  $query ="Select * FROM matpatrul where command='$commandname' and chat_id='$chat_id'";
        $result = DB::table('matpatrul')->where('command', $commandname)->where('chat_id', "$chat_id")->first();
        if (!$result) {
            //   $query ="insert into matpatrul (is_active,chat_id,command) VALUES ('1','$chat_id','$commandname');";
            DB::table('matpatrul')->insert([
                'is_active' => 1,
                'chat_id' => "$chat_id",
                'command' => $commandname
            ]);
            $data = [
                'chat_id' => "$chat_id",
                'text' => "Мат-патруль працює, будьте уважні"
            ];
            Request::sendMessage($data);
        } else {
            if ($result->is_active == 0) {
                $data = [
                    'chat_id' => "$chat_id",
                    'text' => "Мат-патруль працює, будьте уважні"
                ];
                DB::table('matpatrul')->where('command', $commandname)->where('chat_id', "$chat_id")->
                update(['is_active' => 1]);


                Request::sendMessage($data);
            } else if ($result->is_active == 1) {
                DB::table('matpatrul')->where('command', $commandname)->where('chat_id', "$chat_id")->
                update(['is_active' => 0]);

                $data = [
                    'chat_id' => "$chat_id",
                    'text' => "Мат-патруль вимкнено, видихайте"
                ];

                Request::sendMessage($data);
            }
        }


        //  mysqli_close($link);


    }
}
