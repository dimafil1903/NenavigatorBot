<?php


namespace App\Telegram\Commands\truthoraction;


use App\TruthOrActionGame;
use Longman\TelegramBot\Request;

class Game
{

    public $chat_id;

    public function __construct($chat_id)
    {
        $this->chat_id = $chat_id;
    }

    public static function addMemberToGame($user, $chat_id)
    {

        $game = TruthOrActionGame::where('chat_id', "$chat_id")->first();


        $gameFirst = TruthOrActionGame::find($game->id);
        //  dd($gameFirst);
        $players_json = $game->players;

        $players = json_decode($players_json);
        $players = (array)$players;
        //   dd($players);
        if (!$players) {
            $players = [];
            array_push($players, $user);
        } else {
            $userExist = false;
            foreach ($players as $player) {
                if ($user->id == $player->id) {
                    $userExist = true;
                }
            }
            if (!$userExist) {
                array_push($players, $user);
            }
        }


        $players_json = json_encode($players);

        $gameFirst->players = $players_json;
        // dd($gameFirst);
        $gameFirst->save();
        $game = new Game($chat_id);
        $game->updateData();
        // dd($game);
    }

    public function updateData()
    {
        $game = TruthOrActionGame::where('chat_id', "$this->chat_id")->first();
        $gameFirst = TruthOrActionGame::find($game->id);
        $players_json = $game->players;

        $players = json_decode($players_json);

        $playersCollection = collect($players);
        $text = "Ведётся набор в игру \n";
        if ($players) {
            $text .= "В игре: \n";
            foreach ($players as $player) {


                $name = "<a href=\"tg://user?id=$player->id\">$player->first_name</a>";
                $text .= "$name \n";
            }
            $text .= "Всего - " . $playersCollection->count();
        }
        $inline_keyboard = new StartGameKeyboard();
        $inline_keyboard = $inline_keyboard->getKeyboardToJOin($this->chat_id);
        $data = [
            'chat_id' => $gameFirst->chat_id,
            'message_id' => $gameFirst->message_id,
            'reply_markup' => $inline_keyboard,
            'parse_mode' => 'HTML',
            'text' => $text
        ];
        Request::editMessageText($data);
    }

    public static function LeaveFromGame($user, $chat_id)
    {

        $game = TruthOrActionGame::where('chat_id', "$chat_id")->first();


        $gameFirst = TruthOrActionGame::find($game->id);
        //  dd($gameFirst);
        $players_json = $game->players;

        $players = json_decode($players_json);
        $players = (array)$players;
        $i = 0;
        foreach ($players as $player) {

            if ($user->id == $player->id) {
                unset($players[$i]);
            }
            $i++;
        }


        $players_json = json_encode($players);

        $gameFirst->players = $players_json;
        //   dd($gameFirst);
        // dd($gameFirst);
        $gameFirst->save();
        $game = new Game($chat_id);
        $game->updateData();
        // dd($game);
    }

    public static function getGameByToken($token)
    {
        // dd($game);
        return TruthOrActionGame::where('token', "$token")->first();
    }

    public function NewGame()
    {
        TruthOrActionGame::updateOrCreate(
            ['chat_id' => "$this->chat_id"],
            ['status' => 0, 'token' => base64_encode("$this->chat_id")]
        );
    }

    public function getHash()
    {
        $game = TruthOrActionGame::where('chat_id', "$this->chat_id")->first();
        // dd($game);
        return $game->token;
    }

    public function setMessageId($messageId)
    {
        $game = TruthOrActionGame::where('chat_id', "$this->chat_id")->first();
        $gameFirst = TruthOrActionGame::find($game->id);
        $gameFirst->message_id = $messageId;
        $gameFirst->save();
    }

}
