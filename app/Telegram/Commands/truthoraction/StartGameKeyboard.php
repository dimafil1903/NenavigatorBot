<?php


namespace App\Telegram\Commands\truthoraction;


use Longman\TelegramBot\Entities\InlineKeyboard;

class StartGameKeyboard
{


    public function getKeyboardToJOin($chat_id)
    {
        $game = new Game($chat_id);
        $inline_keyboard = new InlineKeyboard([


            [

                ['text' => 'Присоединиться', 'url' => 't.me/NeNavigatorBot?start=' . $game->getHash()],
            ],
            [
                ['text' => 'Начать игру', 'callback_data' => 'startGame'],

            ],
        ]);
        return $inline_keyboard;
    }

    public function getKeyboardToAddUser($chat_id)
    {
        $game = new Game($chat_id);
        $inline_keyboard = new InlineKeyboard([

            [
                ['text' => 'Вступить в игру как 🙍‍♂️', 'callback_data' => 'joinAsMan_' . $chat_id],
                ['text' => 'Вступить в игру как 🙍‍♀️', 'callback_data' => 'joinAsGirl_' . $chat_id],
            ],
        ]);
        return $inline_keyboard;
    }

    public function getKeyboardLeave($chat_id)
    {
        $game = new Game($chat_id);
        $inline_keyboard = new InlineKeyboard([

            [

                ['text' => 'Выйти из игры', 'callback_data' => 'leaveGame_' . $chat_id],
            ],
        ]);
        return $inline_keyboard;
    }
}
