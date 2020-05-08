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

use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 *
 * Command that lists all available commands and displays them in User and Admin sections.
 */
class LeftCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'left';

    /**
     * @var string
     */
    protected $description = 'осталось';

    /**
     * @var string
     */
    protected $usage = '/left';

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
        $from = $message->getFrom();
        $text = trim($message->getText(true));


        $arr = DB::table('leftsettings')->where('chat_id', "$chat_id")->first();

        date_default_timezone_set('Europe/Kiev');
        $added = false;
        $txt = '';
        if ($arr) {
            $workday_set = $arr->workday_settings;
            $workday_set = substr($workday_set, 0, -1);
            $jsony = '{' . $workday_set . '}';
            $ary = json_decode($jsony, true);
            ksort($ary);
            $js = "";
            $isWorkday = false;
            foreach ($ary as $itemqy2 => $valueqy2) {

                if ($itemqy2 == date("N") and $valueqy2 == "Вихідний") {
                    $t = "Сьогодні вихідний";
                    $s = $from->getUsername();
                    if ($s != '') {
                        $sender = "@" . $s;

                    } else {
                        $sender = $from->getFirstName();
                    }
                    $data = [
                        'chat_id' => $chat_id,

                        'text' => $sender . ", " . $t
                    ];
                    Request::sendMessage($data);
                    break;
                } else {
                    $isWorkday = true;
                }
            }

            if ($isWorkday) {

                $cal_set = $arr->calls_settings;
                $json = '{' . $cal_set . '}';
                $ar = json_decode($json, true);

                ksort($ar);
                $txt = "";
                $class_calls_arr = [0 => 0];
                //dd($ar);
                if ($arr->max_par == sizeof($ar) / 2) {

                    $i = 0;
                    // ($ar);
                    foreach ($ar as $itemq => $value) {

                        $i++;

                        if (array_key_exists($i . 'a', $ar)) {

                            array_push($class_calls_arr, [$ar[$i . 'a'], $ar[$i . 'z']]);
                            $class_calls = $class_calls_arr;


                            $today = date("H:i");
                            $t = '';
                            $added = true;

                        }
                        if ($text) {
                            foreach ($class_calls as $para => $time) {
                                if ($para > $arr->max_par) {
                                    unset($class_calls[$para]);
                                }
                            }
                            foreach ($class_calls as $para => $time) {
                                if ($para > $arr->max_par) {
                                    unset($class_calls[$para]);
                                }

                                if ($text == "$para") {

                                    $number_para = (strtotime("00:00:00") - strtotime($today)) + strtotime($time[0]);
                                    $s = date('H:i', $number_para);
                                    $t = "До початку $para-ої пари залишилося: $s год.";

                                }
                            }

                            if ($t == "") {

                                $t = "помилка. Набери правильну цифру...\nНаприклад: /left 2";

                            }


                        }


                    }
                    if ($added) {
                        //  dd($class_calls);
                        foreach ($class_calls as $key => $valued) {
                            if (strtotime($valued[0]) < strtotime($today) && strtotime($valued[1]) >= strtotime($today)) {

                                //dd($valued[1]);
                                $date = strtotime($valued[1]) - (strtotime($today) - strtotime("00:00:00"));

                                $t = date('H:i', $date);

                                $t .= " Залишилось до кінця " . $key . " пари";
                                break;
                            } else {
                                $first_para = (strtotime("00:00:00") - strtotime($today)) + strtotime($class_calls[1][0]);
                                $s = date('H:i', $first_para);
                                $t = "Отдихай, ще нема занять \nДо першої пари залишилося: $s год.";
                                //dd($class_calls,$ar);

                                for ($n = 1; $n < sizeof($ar) / 2; $n++) {

                                    if (strtotime($class_calls[$n][1]) < strtotime($today) and strtotime($class_calls[$n + 1][0]) > strtotime($today)) {


                                        $date = strtotime($class_calls[$n + 1][0]) - (strtotime($today) - strtotime("00:00:00"));
                                        $break = date('i', $date);
                                        //dd($class_calls,$class_calls[$n][1],$class_calls[$n+1][0],$today ,$break);
                                        if ($break == "0:20") {
                                            $t = " До кінця ПЕРЕРВИ - 20 хв.";
                                        } else if ($break == "0:10") {
                                            $t = " До кінця ПЕРЕРВИ - 10 хв.";
                                        } else {
                                            $t = " До кінця ПЕРЕРВИ -  " . ltrim($break, '0') . " хв.";
                                        }


                                    }
                                }
                            }
                        }
                    }
                    $s = $from->getUsername();
                    if ($s != '') {
                        $sender = "@" . $s;

                    } else {
                        $sender = $from->getFirstName();
                    }
                    $data = [
                        'chat_id' => $chat_id,

                        'text' => $sender . ", " . $t
                    ];


                    Request::sendMessage($data);


                }


                //   break;


            }
        } else {
            $s = $from->getUsername();
            if ($s != '') {
                $sender = "@" . $s;

            } else {
                $sender = $from->getFirstName();
            }
            $t = 'Встановіть налаштування цієї команди. Команда з налаштуваннями /leftsettings';
            $data2 = [
                'chat_id' => $chat_id,

                'text' => $sender . ", " . $t
            ];


            Request::sendMessage($data2);
        }
    }

}
