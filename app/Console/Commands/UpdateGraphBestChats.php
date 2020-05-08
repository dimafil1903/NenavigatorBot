<?php


namespace App\Console\Commands;


use App\Message;
use Illuminate\Console\Command;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;

class UpdateGraphBestChats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graphBestChats:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


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
        $topChatsCollection = DB::table('top_chats')->get();
        $arr = [];
        $values = [];
        $labels = [];
        $labels[] = 'days';
        foreach ($topChatsCollection as $chat) {
            $labels[] = $chat->chat_title;
        }

        $arr[] = $labels;
        for ($i = 30; $i >= 0; $i--) {

            $day = today()->subDays($i)->format('Y-m-d');
            $values[] = $day;
            foreach ($topChatsCollection as $chat) {
                $values[] = Message::whereDate('date', today()->subDays($i))->where('chat_id', $chat->chat_id)->count();
            }

            $arr[] = $values;
            $values = [];
        }

        DB::table('graphics_jsons')->updateOrInsert([
            'name' => "bestChatsForMonth"
        ], [
            'json' => json_encode($arr),
        ]);
    }
}
