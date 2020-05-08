<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function show()
    {
        $users_count = DB::table('telegram_users')->count();
        $messages_count = DB::table('digests')->count();
        $streets_count = DB::table('locallities')->count();
        $percent = $users_count / 20000;
        $percent_echo = number_format($percent, 3, ',', ' ');

        return view('start')->with(compact('users_count', 'messages_count', "percent_echo", "streets_count"));
    }
}
