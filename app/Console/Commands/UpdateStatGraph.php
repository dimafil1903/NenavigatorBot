<?php

namespace App\Console\Commands;

use App\Chat;
use App\Message;
use App\TopChat;
use Illuminate\Console\Command;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;

class UpdateStatGraph extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:updateGraph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $router;

    /**
     * Create a new command instance.
     *
     * @param Router $router
     */
    public function __construct()
    {
        parent::__construct();
        // $this->router=$router;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $activeChatsCount = 0;

//        $allChats=Chat::all();
//
//        foreach ($allChats as $chat){
//            if ($this->get_active("$chat->id")){
//                $activeChatsCount++;
//
//            }
//
//            // $countMsg['chat_title']=$chat->title;
//        }
        $allmsg = DB::table('messages')->whereNull('entities')->whereNotNull('text')->get();
        $alm = [];
        foreach ($allmsg as $str) {
            $alm[] = $str->text;
        }
        $array_count = array_count_values($alm);
        $array_count = collect($array_count);

        $array_count = $array_count->sort()->reverse();
        DB::table('topWords')->truncate();
        foreach ($array_count->take(20) as $msg => $count) {

            DB::table('topWords')->updateOrInsert(
                [
                    'msg' => \GuzzleHttp\json_encode($msg)
                ],
                [
                    'count' => $count
                ]
            );
        }

        $chats = Chat::all();
        $topChats = [];
        foreach ($chats as $Chat) {

            $chat_id = (string)$Chat->id;
            if ($this->get_active("$chat_id")) {
                $count = Message::where('chat_id', "$chat_id")->count();

                $topChats[] = ['chat_id' => "$chat_id", 'chat_title' => $Chat->title, "total_msg" => $count];

                // var_dump($this->values);

            }
        }
        $topChatsCollection = collect($topChats);
        $topChatsCollection = $topChatsCollection->sortByDesc('total_msg')->take(10);
        $topChatModel = new TopChat;
        // dd($topChatsCollection);
        DB::table("top_chats")->truncate();
        foreach ($topChatsCollection as $topChat) {
            DB::table("top_chats")->updateOrInsert(
                [
                    'chat_id' => $topChat['chat_id']
                ], [
                    'chat_title' => $topChat['chat_title'],
                    'total_msg' => $topChat['total_msg'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }


    }

    public function get_active($chat_id)
    {
        $count = [];
        for ($i = 30; $i >= 0; $i--) {

            if ($i == 0) {

                $c = Message::whereDate('date', today())->where('chat_id', "$chat_id")->count();
                if ($c != 0) {
                    $count[] = $c;
                }
            } else {

                $c = Message::whereDate('date', today()->subDays($i))->where('chat_id', "$chat_id")->count();
                if ($c != 0) {
                    $count[] = $c;
                }
            }
            $count = collect($count);
            if ($count->isNotEmpty()) {
                return true;
            }
        }
        return false;
    }
}
