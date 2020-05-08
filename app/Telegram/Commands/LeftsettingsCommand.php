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
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 *
 * Command that lists all available commands and displays them in User and Admin sections.
 */
class LeftsettingsCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'leftsettings';

    /**
     * @var string
     */
    protected $description = 'leftsettings';

    /**
     * @var string
     */
    protected $usage = '/leftsettings';

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
        $chat = $message->getChat()->isGroupChat();
        if ($chat) {
            $get_from = $message->getFrom()->getId();
            $admins = Request::getChatMember(['chat_id' => "$chat_id", "user_id" => $get_from]);
            $sender = "";
            $sender_with = "";
            foreach ($admins as $admin_key => $val) {
                if ($admin_key == 'result') {
                    foreach ($val as $res => $value) {
                        if ($res == "user") {
                            foreach ($value as $user => $v) {
                                if ($user == "username") {
                                    $sender = $v;
                                    $sender_with = "@" . $v;
                                }
                                if ($sender == "") {
                                    if ($user == "first_name") {
                                        $sender = $v;
                                        $sender_with = $sender;
                                    }
                                }
                            }
                        }
                        if ($res == "status") {
                            if ($value == "administrator" || $value == "creator") {

                                $inline_keyboard = new InlineKeyboard([[
                                    ['text' => 'Задати максимальну кількість пар', 'callback_data' => 'max_count_of_par'],

                                ], [
                                    ['text' => 'Додати файл з розкладом', 'callback_data' => 'addtimetablefile'],
                                ],
                                    [
                                        ['text' => 'Налаштування дзвінків', 'callback_data' => 'calls_settings'],
                                        ['text' => 'Налаштування робочих днів', 'callback_data' => 'workdays_settings'],
                                    ]]);
                                $data_key = [
                                    'chat_id' => $chat_id,
                                    'text' => 'Виберіть налаштування',
                                    'reply_markup' => $inline_keyboard,
                                ];
                                Request::sendMessage($data_key);


                            } else {
                                Request::sendMessage(['chat_id' => $chat_id, "text" => "$sender_with Смертний, йди звідси. (Цим можна користуватися лише адміністраторам)"]);
                            }

                        }


                    }
                }


            }
        } else {
            $inline_keyboard = new InlineKeyboard([[
                ['text' => 'Задати максимальну кількість пар', 'callback_data' => 'max_count_of_par'],

            ], [
                ['text' => 'Додати файл з розкладом', 'callback_data' => 'addtimetablefile'],
            ],
                [
                    ['text' => 'Налаштування дзвінків', 'callback_data' => 'calls_settings'],
                    ['text' => 'Налаштування робочих днів', 'callback_data' => 'workdays_settings'],
                ]]);
            $data_key = [
                'chat_id' => $chat_id,
                'text' => 'Виберіть налаштування',
                'reply_markup' => $inline_keyboard,
            ];
            Request::sendMessage($data_key);;
        }


    }


}
