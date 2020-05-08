<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpTelegramBot\Laravel\PhpTelegramBotContract;

class UserChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $values = [];
    public $labels = [];

    public function index(PhpTelegramBotContract $telegram_bot)
    {


        $bestChatsForMonth = DB::table('graphics_jsons')->where('name', 'bestChatsForMonth')->first();

        //    $topChatsCollection=  $topChatsCollection->sortByDesc('total_msg');


        return response()->json([
            'Chart' => json_decode($bestChatsForMonth->json),

            // 'triggers'=>$triggers,
            //'triggersCount'=>$triggersCount,
        ]);
    }


    public function del(Request $request)
    {
        $TriggerId = $request->input('triggerId');
        try {
            DB::table('triggers')->delete($TriggerId);
        } catch (\Exception $e) {
        }
        return redirect()->back();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $chat_id
     * @return void
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, PhpTelegramBotContract $telegram_bot)
    {


        $chat_id = "$id";


        $memberCount = \Longman\TelegramBot\Request::getChatMembersCount(
            ['chat_id' => $chat_id]
        );
        // $topChatsCollection=false;


        $chat = Chat::where('id', "$chat_id")->first();

        if ($chat->type != "private") {
            $title = $chat->title;
        } else {
            $title = $chat->first_name . " " . $chat->last_name . " " . $chat->username;
        }
        $arr = [];
        $values = [];
        $labels = [];
        $labels[] = 'days';

        $labels[] = $title;
        $arr[] = $labels;
        for ($i = 30; $i >= 0; $i--) {

            $day = today()->subDays($i)->format('Y-m-d');
            $values[] = $day;

            $values[] = Message::whereDate('date', today()->subDays($i))->where('chat_id', '' . $chat->id . '')->count();


            $arr[] = $values;
            $values = [];
        }
        $count = Message::where('chat_id', "$chat_id")->count();
        $topChats[] = ['id' => "$chat_id", 'title' => $title, "count" => $count];

        // var_dump($this->values);


        return response()->json([
            'Chart' => $arr,
            'membersCount' => $memberCount->getResult(),
            'totalMessages' => $count
        ]);


//dd($triggers);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
