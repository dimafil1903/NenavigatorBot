<?php


namespace Longman\TelegramBot\Commands\SystemCommands;


use Illuminate\Support\Facades\Storage;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class GetstatCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'getstat';

    /**
     * @var string
     */
    protected $description = 'Statistics';

    /**
     * @var string
     */
    protected $usage = '/getstat';

    /**
     * @var string
     */
    protected $version = '1.3.0';

    /**
     * Execute command
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $s = \Longman\TelegramBot\Request::getChat([
            'chat_id' => $chat_id
        ]);
        $res = $s->getResult();
        if (isset($res->photo)) {

            $res = $res->photo['big_file_id'];


            $file = \Longman\TelegramBot\Request::getFile(
                ['file_id' => $res]

            );


            //  dd($file);


            $path = 'https://api.telegram.org/file/bot' . $this->getTelegram()->getApiKey() . '/' . $file->result->file_path;


            $contents = file_get_contents($path);

            Storage::put("public/avatars/$chat_id.jpg", $contents);
        }
        $data = [
            'chat_id' => $chat_id,
            'text' => '[Статистика этого чата](https://nenavigator.pidgorodne.info/stat/' . "$chat_id" . ')' . "\n" .
                '[Статистика всех чатов](https://nenavigator.pidgorodne.info/welcome)' . "\n",
            'parse_mode' => 'markdown'
        ];
        Request::sendMessage($data);
    }
}
