<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

/**
 * Delete chat photo command
 *
 * Gets executed when the photo of a group or channel gets deleted.
 */
class DeletechatphotoCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'deletechatphoto';

    /**
     * @var string
     */
    protected $description = 'Delete chat photo';

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
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $delete_chat_photo = $message->getDeleteChatPhoto();
        $data = [
            'chat_id' => $chat_id,
            'text' => "Правильно, фото было глупое"
        ];
        Request::sendMessage($data);

    }
}
