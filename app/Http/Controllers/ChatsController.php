<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ChatsController extends Controller
{
    public function index()
    {

        $topChatsCollection = DB::table('top_chats')->get();
        return response()->json([

            'topChats' => $topChatsCollection,
            // 'triggers'=>$triggers,
            //'triggersCount'=>$triggersCount,
        ]);
    }
}
