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
 * User "/slap" command
 *
 * Slap a user around with a big trout!
 */
class SlapCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'slap';

    /**
     * @var string
     */
    protected $description = 'Slap someone with their username';

    /**
     * @var string
     */


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
        $words = [
            "дал по ебалу ",
            "плюнул в лицо ",
            "помацал попу ",
            "пощекотал ",
            "надругался над ",
            "ударил ",
            "обнял ",
            "укусил ",
            "потрогал ",
            "двинул ",
            "дал леща ",
            "уебал ",
            "крутанул вертуху ",
            "наблевал на  ",

        ];
        $words2 = [
            "упал с балкона",
            "поплавал с феном в ванной",
            "выпил балтику 7",
            "отсидел 4 пары",
            "ударил мента",
            "поскользнулся",
            "потерял сознание",
            "наступил на гвоздь",
            "попал в аврию",
            "заболел",
            "ахуел",
            "выпал с самолета",
            "повесился",
            "застрелился",

        ];
        $words3 = [
            "и плачет",
            "и умир",
            "и понял жизнь ",
            "и рыгнул",
            "и здох",
            "и сломал ногу",
            "и просит помощи",
            "и теперь в говне ",
            "и хочет справедливости ",
            "и пускает сопли ",
            "и ахуел ",
            "и смеется ",
            "и кричит",
            "и встал ",
            "и сел ",

        ];
        $words4 = [
            "Ему больно",
            "Он кричит",
            "Он понял суть ",
            "Он задохнулся",
            "Он разочаровался",
            "Он отомстил",
            "Он ничего не сделал",
            "Он в безысходности ",
            "Его жизнь не будет прежней ",
            "Его мучает совесть",
            "У него болит жизнь",
            "Он смеется",
            "Он пускает сопли",
            "Он грустит",

        ];
        $message = $this->getMessage();
        $message_id = $message->getMessageId();
        $chat_id = $message->getChat()->getId();
        $text = $message->getText(true);


        $name = $message->getFrom()->getUsername();
        $name_with = "@" . $name;
        if (!$name) {
            $name = $message->getFrom()->getFirstName();
            $name_with = $name;
        }
        $sender = $name_with;
        $botusername = $this->getMessage()->getBotUsername();
        if ($text == "") {
            $text = $sender . ' ' . $words2[mt_rand(0, count($words2) - 1)] . " " . $words3[mt_rand(0, count($words3) - 1)];

        } else if ($text == "@$botusername") {
            $text = "$sender , ало это бот. У него супер-щит";

        } else {

            $text = $sender . ' ' . $words[mt_rand(0, count($words) - 1)] . strtok($text, " ") . " " . $words4[mt_rand(0, count($words4) - 1)];


        }

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];
        $data_message = [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ];

        return Request::sendMessage($data);
    }
}
