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


use App\Chat;
use App\Telegram\Commands\truthoraction\Game;
use App\Telegram\Commands\truthoraction\StartGameKeyboard;
use App\TruthOrActionGame;
use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class StartCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

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

        $token = $message->getText(true);

        if ($token) {

            $game = Game::getGameByToken($token);
            $forwardChat = Chat::find("$game->chat_id");
            $inline_keyboard = new StartGameKeyboard();
            $inline_keyboard = $inline_keyboard->getKeyboardToAddUser($game->chat_id);
            if ($forwardChat) {
                $game = TruthOrActionGame::where('chat_id', "$game->chat_id")->first();


                $gameFirst = TruthOrActionGame::find($game->id);
                //  dd($gameFirst);
                $players_json = $game->players;

                $players = json_decode($players_json);
                $players = (array)$players;
                //   dd($players);
                if (!$players) {
                    $players = [];

                } else {
                    $userExist = false;
                    foreach ($players as $player) {
                        if ($message->getFrom()->getId() == $player->id) {
                            $userExist = true;
                        }
                    }

                    if ($userExist) {

                        $inline_keyboard = new StartGameKeyboard();
                        $inline_keyboard = $inline_keyboard->getKeyboardLeave($game->chat_id);
                        $data = [
                            'chat_id' => $chat_id,
                            'text' => "Вы уже находитесь в игре $forwardChat->title",
                            'reply_markup' => $inline_keyboard,
                        ];
                        return Request::sendMessage($data);
                    }
                }
                $data = [
                    'chat_id' => $chat_id,
                    'text' => $forwardChat->title,
                    'reply_markup' => $inline_keyboard,
                ];

                $game = new Game($forwardChat->id);
                $game->updateData();
                return Request::sendMessage($data);
            }
        } else {

            $data = [
                'chat_id' => $chat_id,
                'text' => 'КУ',

            ];
            return Request::sendMessage($data);
        }


    }
}
