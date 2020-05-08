<?php


namespace App\Telegram\Commands\truthoraction;


use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Request;

class TruthOrAction
{
    /**
     * @var string
     */
    public $chat_id;

    // public $text;
    public function __construct($chat_id)
    {

    }

    public function CreateCategory($message)
    {
        $this->chat_id = $message->getChat()->getId();
        $type = $message->getType();

        $text = $message->getText();

        $arr = str_split($text, 5);
        if (strpos($text, "!cat ") !== false and $arr[0] == "!cat ") {
            $need = substr($text, 4);
            //dd($need);
            DB::table("TruthOrActionCategories")->insert(
                [
                    'name' => $need
                ]
            );
            Request::sendMessage([
                'chat_id' => $this->chat_id,
                'text' => 'added',
            ]);
        }
    }

    public function createQuestion($message)
    {
        $this->chat_id = $message->getChat()->getId();
        $type = $message->getType();

        $text = $message->getText();
        $category_id = "";
        $arr = str_split($text, 3);
        var_dump($arr);
        if (strpos($text, "!q ") !== false and $arr[0] == "!q ") {
            $need = substr($text, 3);

            $exploded = explode("_", $need);
            //dd($need);
            $categories = DB::table("TruthOrActionCategories")->get();
            foreach ($categories as $category) {
                if ($category->id == $exploded[0]) {
                    DB::table("TruthOrActionQuestions")->insert(
                        [
                            'category_id' => $category->id,
                            'question' => $exploded[1]
                        ]
                    );
                    Request::sendMessage([
                        'chat_id' => $this->chat_id,
                        'text' => "added"
                    ]);
                }

            }


        }
    }

    public function CategoriesList($message)
    {
        $this->chat_id = $message->getChat()->getId();
        $type = $message->getType();

        $text = $message->getText();


        if ($text == "!catlist") {

            $categories = DB::table("TruthOrActionCategories")->get();
            $txt = "";
            foreach ($categories as $category) {
                $txt .= "id:" . $category->id . " name:" . $category->name . " /delcat_" . $category->id . "\n";
            }
            Request::sendMessage([
                'chat_id' => $this->chat_id,
                'text' => $txt,
            ]);
        }
    }

//            if ($reply->getMessageId()) {
//
//                $data = [
//                    "chat_id" => $this->chat_id,
//                    "text" => $need . " " . $type
//                ];
//                Request::sendMessage($data);
//
//
//                Request::sendPhoto([
//                    'chat_id' => $this->chat_id,
//                    'photo' => 'AgACAgIAAxkBAAJBb15rg8JxMEXQoARDliFxily7F8V7AAI-rTEbuIpgS78ucuowoO0WB17LDgAEAQADAgADeQADZEYDAAEYBA',
//                ]);
//                // dd($reply->getRawData());
//
//            }


}
