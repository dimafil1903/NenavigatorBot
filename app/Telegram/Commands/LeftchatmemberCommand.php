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

use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;

/**
 * Left chat member command
 *
 * Gets executed when a member leaves the chat.
 */
class LeftchatmemberCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'leftchatmember';

    /**
     * @var string
     */
    protected $description = 'Left Chat Member';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
        $text = $message->getText();
        $msg_id = $message->getMessageId();
        $left = $message->getLeftChatMember();
        $left_id = $left->getId();
        if ($left) {
            $link = mysqli_connect('localhost', 'adminbot', '7C3h0J3l', 'nenavigator')
            or die("Ошибка " . mysqli_error($link));
            mysqli_set_charset($link, 'utf8mb4');
            // выполняем операции с базой данных
            $query = "UPDATE user_chat SET is_left=1 WHERE user_id='$left_id' and chat_id='$chat_id'";
            mysqli_query($link, $query) or die($text = 'ошибка');
            mysqli_close($link);
            $data_left = [
                "chat_id" => "$chat_id",
                "text" => "Пока, " . $left->getFirstName() . ""
            ];


            Request::sendMessage($data_left);

        }

    }
}
