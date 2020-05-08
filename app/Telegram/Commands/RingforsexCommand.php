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
 * User "/help" command
 *
 * Command that lists all available commands and displays them in User and Admin sections.
 */
class RingforsexCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'ringforsex';

    /**
     * @var string
     */
    protected $description = 'рандомная фотография девушки';

    /**
     * @var string
     */
    protected $usage = '/ringforsex';

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

        $one_or_2 = random_int(1, 2);
        $img = 'https://blog.hubspot.com/hubfs/502-bad-gateway-error.jpg';
        if ($one_or_2 == 1) {
            $url = 'https://api.unsplash.com/photos/random?collections=8760375&client_id=aee5e93465d9d72f7ed4dda32088ed585aef59d8d46c8bd4a42f7d1e6ef1c002'; // path to your JSON file
            $data = file_get_contents($url); // put the contents of the file into a variable
            $characters = json_decode($data);
            $img = "";
            foreach ($characters as $character => $val) {

                if ($character == 'urls') {
                    foreach ($val as $item => $value) {
                        if ($item == 'regular') {
                            $img = $value;
                        }

                    }

                }
            }
        } else if ($one_or_2 == 2) {

            $random_number = rand(1, 559);
            $queryFields = [
                "query" => "underwear bikini",
                "per_page" => 1,
                'page' => $random_number
            ];

            $options = [
                CURLOPT_URL => "https://api.pexels.com/v1/search?" . http_build_query($queryFields),
                CURLOPT_USERAGENT => "php/curl",
                CURLOPT_HTTPHEADER => [
                    "Authorization:563492ad6f9170000100000164bad3f025214fa4bb15c0f3eb72ecd3"
                ],
                CURLOPT_RETURNTRANSFER => 1
            ];
            $handle = curl_init();
            curl_setopt_array($handle, $options);
            $response = curl_exec($handle);
            curl_close($handle);

            $decodedResponse = json_decode($response);
            if ($decodedResponse) {
                foreach ($decodedResponse as $value => $item) {
                    $text = $value;
                    if ($value == 'photos') {
                        foreach ($item as $v => $i) {
                            foreach ($i as $t => $need) {
                                if ($t == 'src') {
                                    foreach ($need as $size => $phto) {
                                        if ($size == 'original') {

                                            $img = $phto;
                                        }

                                    }
                                }

                            }

                        }
                    }

                }
            }


        }

        $photo_data = [
            'chat_id' => $chat_id,
            'photo' => $img,
            "reply_to_message_id" => $message_id
        ];
        return Request::sendPhoto($photo_data);


    }


}
