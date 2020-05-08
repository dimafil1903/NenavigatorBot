<?php

namespace App\Http\Controllers;

use App\Deputy;
use App\DeputyLocality;
use App\Locallity;
use PhpTelegramBot\Laravel\PhpTelegramBotContract;

class TelegramController extends Controller
{
    public function set(PhpTelegramBotContract $telegram_bot)
    {
        return $telegram_bot->setWebhook(env('APP_URL') . '/hook');
    }

    public function unset(PhpTelegramBotContract $telegram_bot)
    {
        return $telegram_bot->deleteWebhook();
    }

    public function hook(PhpTelegramBotContract $telegram_bot)
    {

        $telegram_bot->handle();

    }

    public function info(PhpTelegramBotContract $telegram_bot)
    {
        $telegram_bot->handleGetUpdates();

    }
}
