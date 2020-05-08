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
use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class StartGameCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'startGame';

    /**
     * @var string
     */
    protected $description = 'Старт игры правда или действие';

    /**
     * @var string
     */
    protected $usage = '/startGame';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $private_only = false;


    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();


        $game = new Game($chat_id);

        $game->NewGame();


        $inline_keyboard = new StartGameKeyboard();
        $inline_keyboard = $inline_keyboard->getKeyboardToJOin($chat_id);

        $startGameText = "Ведётся набор в игру";
        $data_key = [
            'chat_id' => $chat_id,
            'text' => $startGameText,
            'reply_markup' => $inline_keyboard,
        ];
        $result = Request::sendMessage($data_key)->getResult();
        $message_id = $result->message_id;
        $game->setMessageId($message_id);
        $data = [
            'chat_id' => $chat_id,
            'text' => 'КУ',

        ];
        Request::sendMessage($data);


    }
}
