<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class getDeclaration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'declarations:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        //   dd('hello');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // dd('hello');
        $people = DB::table('deputies')->get();
        if ($people->isNotEmpty()) {

            foreach ($people as $person) {

                $json = $this->get_json($person->lastName, $person->firstName, $person->middleName);

                //var_dump($json);
                if ($json) {
                    if (array_key_exists('items', $json)) {
                        $items = $json->items;
                        //    dd($items);


                        //   dd($json);
                        $info = '';
                        $i = 0;
                        foreach ($items as $item) {
                            //  $info.=$item;
                            // var_dump($item);
                            $api_id = null;
                            $placeOfWork = null;
                            $position = null;
                            $linkPDF = null;
                            $firstname = null;
                            $lastname = null;

                            foreach ($item as $data => $key) {
                                switch ($data) {
                                    case 'id':
                                        $api_id = $key;
                                    case 'firstname':
                                        $firstname = $key;
                                    case 'lastname':
                                        $lastname = $key;
                                    case 'placeOfWork':
                                        $placeOfWork = $key;
                                    case 'position':
                                        $position = $key;
                                    case 'linkPDF':
                                        $linkPDF = $key;
                                }

                            }
                            $declaration = DB::table('declarations')->where('api_id', $api_id)->first();
                            if (!$declaration) {
                                if (mb_strtoupper($lastname, 'UTF-8') == mb_strtoupper($person->lastName, 'UTF-8') and mb_strtoupper($firstname, 'UTF-8') == mb_strtoupper($person->firstName . ' ' . $person->middleName, 'UTF-8')) {


                                    $today = date("Y-m-d H:i:s");
                                    DB::table('declarations')->insert([
                                        ['api_id' => $api_id, 'person_id' => $person->id, 'place_of_work' => $placeOfWork, 'position' => $position, 'linkPDF' => $linkPDF, 'created_at' => $today]
                                    ]);
                                    $i++;
                                    //echo mb_strtoupper($person->last_name, 'UTF-8');
                                }
                                // var_dump( $public_id.':'.$placeOfWork.':'.$position.':'.' '.$linkPDF);
                            }
                            //  var_dump($person->last_name);
                        }
                        if ($i > 0) {
                            $this->info($person->lastName . ' ' . $person->firstName . ' - ' . $i . ' Декларацій');
                        } else {
                            $this->info($person->lastName . ' ' . $person->firstName . ' - Нема що передавати');
                        }
                    }
                }

            }

        }


    }

    public function get_json($lastName, $firstName, $middleName)
    {
        sleep(7);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://public-api.nazk.gov.ua/v1/declaration/?q=" . $lastName . "+" . $firstName . "+" . $middleName . '&declarationType=&declarationYear=2019&documentType=&positionTypes%5B%5D=3&dtStart=&dtEnd=&isRisk=',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",

            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($response);

        return $json;
    }
}
