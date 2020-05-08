<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 *
 * Command that lists all available commands and displays them in User and Admin sections.
 */
class TestCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'test';

    /**
     * @var string
     */
    protected $description = 'test';

    /**
     * @var string
     */
    protected $usage = '/test';

    /**
     * @var string
     */
    protected $version = '1.3.0';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $link = mysqli_connect('localhost', 'adminbot', '7C3h0J3l', 'nenavigator')
        or die("Ошибка " . mysqli_error($link));
        mysqli_set_charset($link, 'utf8mb4');
        // выполняем операции с базой данных
        $query = "SELECT first_name FROM user left join user_chat on user.id=user_chat.user_id where user_chat.chat_id= '$chat_id' and user_chat.is_left IS NULL; ";
        $result = mysqli_query($link, $query) or die($text = 'ошибка');
        $b = "";
        foreach ($result as $key => $s) {
            foreach ($s as $k) {
                $b .= $k . " ";


            }
        }
        mysqli_close($link);
        $member = [
            'chat_id' => $chat_id,

        ];
        $data_message = [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ];
        Request::deleteMessage($data_message);
        $a = Request::getChatMembersCount($member);


        $ar = Request::getChatAdministrators(["chat_id" => $chat_id])->getRawData();


        implode(', ', array_map(function ($entry) {

            return $entry['Name'];

        }, $ar));
        $data = [
            'chat_id' => $chat_id,
            'parse_mode' => 'markdown',
            'text' => implode((array)$ar)
        ];


        Request::sendMessage($data);
    }


}
