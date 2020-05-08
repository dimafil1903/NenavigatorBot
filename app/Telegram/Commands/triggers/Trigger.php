<?php


namespace App\Telegram\Commands\triggers;


use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Request;

class Trigger
{
    /**
     * @var string
     */
    public $chat_id;

    // public $text;
    public function __construct($chat_id)
    {

    }

    public function CreateTrigger($message)
    {

        $this->chat_id = $message->getChat()->getId();
        $reply = $message->getReplyToMessage();

        $type = $message->getType();
        if ($reply) {


            if ($type == "text") {
                $text = $message->getText();

                $arr = str_split($text, 9);
                if (strpos($text, "!trigger ") !== false and $arr[0] == "!trigger ") {
                    $need = substr($text, 8);

                    $replyType = $reply->getType();
                    if ($replyType == "text")
                        $this->InsertTrigger($reply->getText(), $need, $replyType, $type);
//                    if($replyType=="photo") {
//                        $photoArr=$reply->getPhoto();
//                   //     dd($photoArr);
//                        $this->InsertTrigger($photoArr[0]->file_id, $need, $replyType, $type);
//                    }if($replyType=="document") {
//                        $docArr=$reply->getDocument();
//                        $this->InsertTrigger($docArr[0]->file_id, $need, $replyType, $type);
//                    }
                    if ($replyType == "sticker") {
                        $sticker = $reply->getSticker();
                        //  dd($docArr);
                        $this->InsertTrigger($sticker->file_unique_id, $need, $replyType, $type);
                    }
                }
            } else if ($type == 'photo') {
                $text = $message->getCaption();

                $arr = str_split($text, 8);
                if ((strpos($text, "!trigger") !== false && $arr[0] == "!trigger")) {

                    $photoArr = $message->getPhoto();
                    $photo = $photoArr[0]->file_id;
                    $replyType = $reply->getType();
                    if ($replyType == "text")
                        $this->InsertTrigger($reply->getText(), $photo, $replyType, $type);
//                    if($replyType=="photo") {
//                        $photoArr=$reply->getPhoto();
//                        //     dd($photoArr);
//                        $this->InsertTrigger($photoArr[0]->file_id, $photo, $replyType, $type);
//                    }if($replyType=="document") {
//                        $docArr=$reply->getDocument();
//                        $this->InsertTrigger($docArr[0]->file_id, $photo, $replyType, $type);
//                    }
                    if ($replyType == "sticker") {
                        $sticker = $reply->getSticker();
                        //  dd($docArr);
                        $this->InsertTrigger($sticker->file_unique_id, $photo, $replyType, $type);
                    }
                }
            } else if ($type == 'video') {
                $text = $message->getCaption();

                $arr = str_split($text, 8);
                if ((strpos($text, "!trigger") !== false && $arr[0] == "!trigger")) {

                    $video = $message->getVideo();
                    // dd($video);
                    $video = $video->file_id;
                    $replyType = $reply->getType();
                    if ($replyType == "text")
                        $this->InsertTrigger($reply->getText(), $video, $replyType, $type);
//                    if($replyType=="photo") {
//                        $photoArr=$reply->getPhoto();
//                        //     dd($photoArr);
//                        $this->InsertTrigger($photoArr[0]->file_id, $photo, $replyType, $type);
//                    }if($replyType=="document") {
//                        $docArr=$reply->getDocument();
//                        $this->InsertTrigger($docArr[0]->file_id, $photo, $replyType, $type);
//                    }
                    if ($replyType == "sticker") {
                        $sticker = $reply->getSticker();
                        //  dd($docArr);
                        $this->InsertTrigger($sticker->file_unique_id, $video, $replyType, $type);
                    }
                }
            } else if ($type == 'document') {
                $text = $message->getCaption();

                $arr = str_split($text, 8);
                if ((strpos($text, "!trigger") !== false && $arr[0] == "!trigger")) {

                    $docArr = $message->getDocument();

                    $doc = $docArr->file_id;
                    $replyType = $reply->getType();
                    if ($replyType == "text")
                        $this->InsertTrigger($reply->getText(), $doc, $replyType, $type);
//                    if($replyType=="photo") {
//                        $photoArr=$reply->getPhoto();
//                        //     dd($photoArr);
//                        $this->InsertTrigger($photoArr[0]->file_id, $photo, $replyType, $type);
//                    }if($replyType=="document") {
//                        $docArr=$reply->getDocument();
//                        $this->InsertTrigger($docArr[0]->file_id, $photo, $replyType, $type);
//                    }
                    if ($replyType == "sticker") {
                        $sticker = $reply->getSticker();
                        //  dd($docArr);
                        $this->InsertTrigger($sticker->file_unique_id, $doc, $replyType, $type);
                    }
                }

            } else if ($type == 'audio') {
                $text = $message->getCaption();

                $arr = str_split($text, 8);
                if ((strpos($text, "!trigger") !== false && $arr[0] == "!trigger")) {

                    $docArr = $message->getAudio();

                    $doc = $docArr->file_id;
                    $replyType = $reply->getType();
                    if ($replyType == "text")
                        $this->InsertTrigger($reply->getText(), $doc, $replyType, $type);
//                    if($replyType=="photo") {
//                        $photoArr=$reply->getPhoto();
//                        //     dd($photoArr);
//                        $this->InsertTrigger($photoArr[0]->file_id, $photo, $replyType, $type);
//                    }if($replyType=="document") {
//                        $docArr=$reply->getDocument();
//                        $this->InsertTrigger($docArr[0]->file_id, $photo, $replyType, $type);
//                    }
                    if ($replyType == "sticker") {
                        $sticker = $reply->getSticker();
                        //     dd($sticker);
                        $this->InsertTrigger($sticker->file_unique_id, $doc, $replyType, $type);
                    }
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
    }

    /**
     * @return mixed
     */
    public function InsertTrigger($trigger_msg, $anser_msg, $msg_type, $answer_type)
    {
        DB::table('triggers')->
        insert(
            [
                'chat_id' => "$this->chat_id",
                'trigger_message' => $trigger_msg,//json_encode($reply->getRawData()),
                'answer_message' => $anser_msg,
                'message_type' => $msg_type,
                'answer_type' => $answer_type
            ]
        );

    }

    public function CreateReTrigger($message)
    {
        $reply = $message->getReplyToMessage();

        $type = $message->getType();
        if ($reply) {


            if ($type == "text") {
                $text = $message->getText();

                $arr = str_split($text, 11);
                if (strpos($text, "!retrigger ") !== false and $arr[0] == "!retrigger ") {
                    $need = substr($text, 11);

                    $replyType = $reply->getType();
                    if ($replyType == "text")
                        $this->InsertTrigger($need, $reply->getText(), $type, $replyType);
//                    if($replyType=="photo") {
//                        $photoArr=$reply->getPhoto();
//                   //     dd($photoArr);
//                        $this->InsertTrigger($photoArr[0]->file_id, $need, $replyType, $type);
//                    }if($replyType=="document") {
//                        $docArr=$reply->getDocument();
//                        $this->InsertTrigger($docArr[0]->file_id, $need, $replyType, $type);
//                    }
                    if ($replyType == "sticker") {
                        $sticker = $reply->getSticker();
                        //  dd($docArr);
                        $this->InsertTrigger($need, $sticker->file_id, $type, $replyType);
                    }
                }
            } else if ($type == 'photo') {
                $text = $message->getCaption();

                $arr = str_split($text, 11);
                if (strpos($text, "!retrigger") !== false and $arr[0] == "!retrigger") {
                    //  $need = substr($text, 10);
                    $photoArr = $message->getPhoto();
                    $photo = $photoArr[0]->file_id;
                    $replyType = $reply->getType();
                    if ($replyType == "text")
                        $this->InsertTrigger($photo, $reply->getText(), $type, $replyType);
//                    if($replyType=="photo") {
//                        $photoArr=$reply->getPhoto();
//                        //     dd($photoArr);
//                        $this->InsertTrigger($photoArr[0]->file_id, $photo, $replyType, $type);
//                    }if($replyType=="document") {
//                        $docArr=$reply->getDocument();
//                        $this->InsertTrigger($docArr[0]->file_id, $photo, $replyType, $type);
//                    }
                    if ($replyType == "sticker") {
                        $sticker = $reply->getSticker();
                        //  dd($docArr);
                        $this->InsertTrigger($photo, $sticker->file_id, $type, $replyType);
                    }
                }
            } else if ($type == 'document') {
                $text = $message->getCaption();

                $arr = str_split($text, 11);
                if (strpos($text, "!retrigger") !== false and $arr[0] == "!retrigger") {
                    //     $need = substr($text, 10);
                    $docArr = $message->getDocument();

                    $doc = $docArr->file_id;
                    $replyType = $reply->getType();
                    if ($replyType == "text")
                        $this->InsertTrigger($doc, $reply->getText(), $type, $replyType);
//                    if($replyType=="photo") {
//                        $photoArr=$reply->getPhoto();
//                        //     dd($photoArr);
//                        $this->InsertTrigger($photoArr[0]->file_id, $photo, $replyType, $type);
//                    }if($replyType=="document") {
//                        $docArr=$reply->getDocument();
//                        $this->InsertTrigger($docArr[0]->file_id, $photo, $replyType, $type);
//                    }
                    if ($replyType == "sticker") {
                        $sticker = $reply->getSticker();
                        //  dd($docArr);
                        $this->InsertTrigger($doc, $sticker->file_id, $type, $replyType);
                    }
                }

            } else if ($type == 'audio') {
                $text = $message->getCaption();

                $arr = str_split($text, 11);
                if (strpos($text, "!retrigger") !== false and $arr[0] == "!retrigger") {
                    $need = substr($text, 10);
                    $docArr = $message->getAudio();

                    $doc = $docArr->file_id;
                    $replyType = $reply->getType();
                    if ($replyType == "text")
                        $this->InsertTrigger($doc, $reply->getText(), $type, $replyType);
//                    if($replyType=="photo") {
//                        $photoArr=$reply->getPhoto();
//                        //     dd($photoArr);
//                        $this->InsertTrigger($photoArr[0]->file_id, $photo, $replyType, $type);
//                    }if($replyType=="document") {
//                        $docArr=$reply->getDocument();
//                        $this->InsertTrigger($docArr[0]->file_id, $photo, $replyType, $type);
//                    }
                    if ($replyType == "sticker") {
                        $sticker = $reply->getSticker();
                        //     dd($sticker);
                        $this->InsertTrigger($doc, $sticker->file_id, $type, $replyType);
                    }
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
    }

    public function getTrigger($message)
    {
        $type = $message->getType();
        $data = [];
        if ($type == "text") {
            $text = $message->getText();
            $triggers = DB::table('triggers')
                ->where('trigger_message', $message->getText())
                ->where('message_type', $type)
                ->where('chat_id', "$this->chat_id")
                ->get();
            $this->SendAnswer($triggers);
//dd($triggers);
        } else
            if ($type == "sticker") {
                $sticker = $message->getSticker();
                //  dd($sticker->file_unique_id);
                $triggers = DB::table('triggers')
                    ->where('trigger_message', $sticker->file_unique_id)
                    ->where('message_type', $type)
                    ->where('chat_id', "$this->chat_id")
                    ->get();
                if ($triggers->isNotEmpty()) {
                    $this->SendAnswer($triggers);
                }
            }
    }

    public function SendAnswer($triggers)
    {
        $data['chat_id'] = "$this->chat_id";
        $answers = [];
        $audioAnswers = [];
        $stickerAnswer = [];
        $randomType = [
        ];

        foreach ($triggers as $trigger) {
            if ($trigger->answer_type == 'text') {
                $answers[] = $trigger->answer_message;
                $randomType["text"] = true;
            }
            if ($trigger->answer_type == 'photo') {
                $data['photo'] = $trigger->answer_message;

                $randomType["photo"] = true;
            }
            if ($trigger->answer_type == 'document') {
                $data['document'] = $trigger->answer_message;
                // Request::sendMessage($data);

                $randomType["document"] = true;
            }
            if ($trigger->answer_type == 'video') {
                $data['video'] = $trigger->answer_message;
                // Request::sendMessage($data);

                $randomType["video"] = true;
            }
            if ($trigger->answer_type == 'audio') {
                $audioAnswers[] = $trigger->answer_message;
                // $data['audio']=$trigger->answer_message;
                // Request::sendMessage($data);
                $randomType["audio"] = true;
            }
            if ($trigger->answer_type == 'sticker') {
                $stickerAnswer[] = $trigger->answer_message;
                // $data['audio']=$trigger->answer_message;
                // Request::sendMessage($data);
                $randomType["sticker"] = true;

            }
        }
        // dd($stickerAnswer);
        if (!empty($randomType)) {


            $rndIndex = array_rand($randomType);

//          Request::sendMessage([
//              'chat_id'=>481629579,
//              'text'=>$rndIndex
//          ]);
            $randomType[$rndIndex] = "send";
            if (array_key_exists('text', $randomType)) {
                if ($randomType['text'] === "send") {
                    if (!empty($answers)) {
                        $randIndex = array_rand($answers);
                        $data['text'] = $answers[$randIndex];
                        Request::sendMessage($data);
                    }
                }

            }
            if (array_key_exists('photo', $randomType)) {
                if ($randomType['photo'] === "send") {
                    Request::sendPhoto($data);
                }
            }
            if (array_key_exists('document', $randomType)) {
                if ($randomType['document'] === "send") {
                    Request::sendDocument($data);
                }
            }
            if (array_key_exists('video', $randomType)) {
                if ($randomType['video'] === "send") {
                    Request::sendVideo($data);
                }
            }
            if (array_key_exists('audio', $randomType)) {
                if ($randomType['audio'] === "send") {
                    // $data['text']="";
                    if (!empty($audioAnswers)) {
                        $rndIndex = array_rand($audioAnswers);
                        $data['audio'] = $audioAnswers[$rndIndex];
                        Request::sendAudio($data);
                    }
                }
            }
            if (array_key_exists('sticker', $randomType)) {

                if ($randomType['sticker'] === "send") {
                    // $data['text']="";

                    if (!empty($stickerAnswer)) {
                        $rndIndex = array_rand($stickerAnswer);
                        $data['sticker'] = $stickerAnswer[$rndIndex];
                        Request::sendSticker($data);
                    }
                }
            }
        }
    }


}
