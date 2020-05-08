<?php


namespace Longman\TelegramBot\Commands\UserCommands;


use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;


class TriggerslistCommand extends UserCommand
{

    protected $name = 'triggerslist';

    /**
     * @var string
     */
    protected $description = 'triggersList';

    /**
     * @var string
     */
    protected $usage = '/triggerslist';

    /**
     * @var string
     */
    protected $version = '1.3.0';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $chat_id = $this->getMessage()->getChat()->getId();
        $triggers = DB::table('triggers')->where('chat_id', "$chat_id")->get();
        $msg = '';
        $count = 1;
        $sended_flag = false;
        if ($triggers->isNotEmpty()) {
            foreach ($triggers as $trigger) {
                $msg .= $count . ". trigger: " . $trigger->trigger_message . " answer: " . $trigger->answer_message . " /deltrig" . $trigger->id . "\n";
                $count++;

            }

            Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => "$msg",
            ]);
        } else {
            Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => "Нету триггеров",
            ]);
        }
    }


}
