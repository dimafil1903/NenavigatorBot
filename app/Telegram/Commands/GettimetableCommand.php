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
class GettimetableCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'gettimetable';

    /**
     * @var string
     */
    protected $description = 'Файл з розкладом';

    /**
     * @var string
     */
    protected $usage = '/gettimetable';

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

        $result = DB::table('leftsettings')->where('chat_id', "$chat_id")->first();
        $file_src = '';

        $sr = $result->file_path;
        $data = [
            'chat_id' => "$chat_id",
            'document' => Request::encodeFile("/var/www/adminbot/data/www/nenavigator.pidgorodne.info/storage/telergam/download/$sr"),
            'caption' => 'Тримай, вчись на здровья 😁'
        ];


        Request::sendDocument($data);


    }


}
