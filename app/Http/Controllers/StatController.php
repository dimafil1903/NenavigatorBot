<?php


namespace App\Http\Controllers;


use App\Message;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{

    public function index()
    {
        $count_messages = DB::table('messages')->count();
        $get_chats = DB::table('chats')->count();
        $triggers_count = DB::table('triggers')->count();
        $topMsges = DB::table('topWords')->get();


        foreach ($topMsges as $msg) {
            $msg->msg = \GuzzleHttp\json_decode($msg->msg);

        }


//dd($countMsg);


        return response()->json([
            'messages' => $count_messages,
            'triggersCount' => $triggers_count,
            'countOfChats' => $get_chats,
            'topMsg' => $topMsges
            //     'activeChats'=>$activeChatsCount,
        ]);
    }

    public function get_active($chat_id)
    {
        $count = [];
        for ($i = 10; $i >= 0; $i--) {

            if ($i == 0) {

                $c = Message::whereDate('date', today())->where('chat_id', $chat_id)->count();
                if ($c != 0) {
                    $count[] = $c;
                }
            } else {

                $c = Message::whereDate('date', today()->subDays($i))->where('chat_id', $chat_id)->count();
                if ($c != 0) {
                    $count[] = $c;
                }
            }


        }
        $count = collect($count);
        if ($count->isNotEmpty()) {
            return true;
        } else {
            return false;
        }
    }

}
