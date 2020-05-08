<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class getDeclarationId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'declaration:id';

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
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $declarations = DB::table('declarations')->get();


        if ($declarations->isNotEmpty()) {
            $i = 0;
            foreach ($declarations as $declaration) {

                $existed_declaration = DB::table('big_declarations')->where('api_id', $declaration->api_id)->first();
                if (!$existed_declaration) {
                    $result = $this->get_json($declaration->api_id);

                    if ($result) {

                        $a = 0;
                        $existed_rows = [];
                        for ($a = 0; $a < 17; $a++) {
                            if (array_key_exists('step_' . $a, $result[0])) {
                                $existed_rows[] = $a;
                            }
                        }
                        $step_0 = null;
                        $step_1 = null;
                        $step_2 = null;
                        $step_3 = null;
                        $step_4 = null;
                        $step_5 = null;
                        $step_6 = null;
                        $step_7 = null;
                        $step_8 = null;
                        $step_9 = null;
                        $step_10 = null;
                        $step_11 = null;
                        $step_12 = null;
                        $step_13 = null;
                        $step_14 = null;
                        $step_15 = null;
                        $step_16 = null;
                        foreach ($existed_rows as $key) {
                            switch ($key) {
                                case 0:
                                    $step_0 = $result[0]->step_0;
                                case 1:
                                    $step_1 = $result[0]->step_1;
                                case 2:
                                    $step_2 = $result[0]->step_2;
                                case 3:
                                    $step_3 = $result[0]->step_3;
                                case 4:
                                    $step_4 = $result[0]->step_4;
                                case 5:
                                    $step_5 = $result[0]->step_5;
                                case 6:
                                    $step_6 = $result[0]->step_6;
                                case 7:
                                    $step_7 = $result[0]->step_7;
                                case 8:
                                    $step_8 = $result[0]->step_8;
                                case 9:
                                    $step_9 = $result[0]->step_9;
                                case 10:
                                    $step_10 = $result[0]->step_10;
                                case 11:
                                    $step_11 = $result[0]->step_11;
                                case 12:
                                    $step_12 = $result[0]->step_12;
                                case 13:
                                    $step_13 = $result[0]->step_13;
                                case 14:
                                    $step_14 = $result[0]->step_14;
                                case 15:
                                    $step_15 = $result[0]->step_15;
                                case 16:
                                    $step_16 = $result[0]->step_16;
                                //        $step_17 = $result[0]->step_17;
                            }
                        }
                        if ($step_0 and $step_1) {

                            $arr_step1 = array($step_1);
                            if (array_key_exists('region', $step_1)) {
                                if ($step_1->region == "1.2.12") {


                                    $is_changed = false;
                                    if (array_key_exists('changesYear', $step_0)) {
                                        $is_changed = true;
                                    }
                                    if (!$is_changed) {
                                        $i++;
                                        if (!array_key_exists('empty', $step_0) and !array_key_exists('empty', $step_1)) {

                                            $headers = ['#', 'lastname', 'firstname', 'middlename'];
                                            $this->table($headers, [[$i, $step_1->lastname, $step_1->firstname, $step_1->middlename]]);

                                            $declarationYear1 = null;
                                            $declarationYear3 = null;
                                            $declarationYear4 = null;
                                            $declarationYearTo = null;
                                            $declarationYearFrom = null;
                                            $declarationType = null;
                                            $cityType = null;
                                            $responsiblePosition = null;
                                            $corruptionAffected = null;


                                            //ПРОВЕРКА НА СУЩЕСТВОВАНИЕ ПОЛЕЙ В ЗАПРОСЕ
                                            if (array_key_exists('declarationYear1', $step_0)) {
                                                $declarationYear1 = $step_0->declarationYear1;
                                            } else if (array_key_exists('declarationYear3', $step_0)) {
                                                $declarationYear3 = $step_0->declarationYear3;
                                            } else if (array_key_exists('declarationYear4', $step_0)) {
                                                $declarationYear4 = $step_0->declarationYear4;
                                            } else if (array_key_exists('declarationYearTo', $step_0)) {
                                                $declarationYearTo = $step_0->declarationYearTo;
                                            } else if (array_key_exists('declarationYearFrom', $step_0)) {
                                                $declarationYearFrom = $step_0->declarationYearFrom;
                                            }
                                            if (array_key_exists('declarationType', $step_0)) {
                                                $declarationType = $step_0->declarationType;
                                            }
                                            if (array_key_exists('cityType', $step_1)) {
                                                $cityType = $step_1->cityType;
                                            }
                                            if (array_key_exists('responsiblePosition', $step_1)) {
                                                $responsiblePosition = $step_1->responsiblePosition;
                                            }
                                            if (array_key_exists('corruptionAffected', $step_1)) {
                                                $corruptionAffected = $step_1->corruptionAffected;
                                            }

                                            DB::table('big_declarations')->insertOrIgnore([
                                                'api_id' => $declaration->api_id,
                                                'created_date' => $result[1],
                                                'declarationType' => $declarationType,
                                                'declarationYear1' => $declarationYear1,
                                                'declarationYear3' => $declarationYear3,
                                                'declarationYear4' => $declarationYear4,
                                                'declarationYearTo' => $declarationYearTo,
                                                'declarationYearFrom' => $declarationYearFrom,
                                                'lastName' => $step_1->lastname,
                                                'FirstName' => $step_1->firstname,
                                                'MiddleName' => $step_1->middlename,
                                                'PostType' => $step_1->postType,
                                                'workPost' => $step_1->workPost,
                                                'workPlace' => $step_1->workPlace,
                                                'responsiblePosition' => $responsiblePosition,
                                                'corruptionAffected' => $corruptionAffected,
                                                'cityType' => $cityType,
                                                "created_at" => \Carbon\Carbon::now(),
                                            ]);

                                        } else {
                                            echo "error 1";
                                        }


                                        if ($this->check_step($step_2)) {
                                            foreach ($step_2 as $person_id => $value) {

                                                DB::table('relationships')->insertOrIgnore([
                                                    'ref_id' => $declaration->api_id,
                                                    'person_id' => $person_id,
                                                    'lastname' => $value->lastname,
                                                    'firstname' => $value->firstname,
                                                    'middlename' => $value->middlename,

                                                    'subjectRelation' => $value->subjectRelation,

                                                    "created_at" => \Carbon\Carbon::now(),
                                                ]);

                                                // echo $value->firstname;

                                            }
                                            $this->info("Члены семьи добавлены в базу успешно");
                                        }
                                        if ($this->check_step($step_3)) {
                                            foreach ($step_3 as $key => $value) {
                                                //var_dump($key,$value);
                                                $value = (array)$value;
                                                DB::table('real_estates')->insertOrIgnore([
                                                    'ref_id' => $declaration->api_id,
                                                    'obj_id' => $key,
                                                    'person' => $this->check_and_get('person', $value),
                                                    'country' => $this->check_and_get('country', $value),
                                                    'objectType' => $this->check_and_get('objectType', $value),
                                                    'otherObjectType' => $this->check_and_get('otherObjectType', $value),
                                                    'totalArea' => $this->check_and_get('totalArea', $value),
                                                    'owningDate' => $this->check_and_get('owningDate', $value),
                                                    'ua_cityType' => $this->check_and_get('ua_cityType', $value),
                                                    'costDate' => $this->check_and_get('costDate', $value),
                                                    'costAssessment' => $this->check_and_get('costAssessment', $value),
                                                    "created_at" => \Carbon\Carbon::now(),
                                                ]);
                                                foreach ($value as $key_value => $value_value) {


                                                    if ($key_value == 'rights') {
                                                        foreach ($value_value as $person => $data_rights) {
                                                            $data_rights = (array)$data_rights;
                                                            DB::table('rights')->insertOrIgnore([
                                                                'ref_id' => $declaration->api_id,
                                                                'type_id' => '3',
                                                                'obj_id' => $key,
                                                                'person_id' => $person,
                                                                'ownershipType' => $this->check_and_get('ownershipType', $data_rights),
                                                                'ua_lastname' => $this->check_and_get('ua_lastname', $data_rights),
                                                                'ua_firstname' => $this->check_and_get('ua_firstname', $data_rights),
                                                                'ua_middlename' => $this->check_and_get('ua_middlename', $data_rights),
                                                                'percentOwnership' => $this->check_and_get('percentOwnership', $data_rights),
                                                                'eng_company_code' => $this->check_and_get('eng_company_code', $data_rights),
                                                                'eng_company_name' => $this->check_and_get('eng_company_name', $data_rights),
                                                                'ukr_company_name' => $this->check_and_get('ukr_company_name', $data_rights),
                                                                'eng_company_address' => $this->check_and_get('eng_company_address', $data_rights),
                                                                'ukr_company_address' => $this->check_and_get('ukr_company_address', $data_rights),

                                                                "created_at" => \Carbon\Carbon::now(),
                                                            ]);
                                                            //   var_dump($data_rights->ownershipType);
                                                        }
                                                    }
                                                }


                                            }
                                            $this->info("Недвижимость добавлена");
                                        }


                                        /**
                                         *
                                         * TODO STEP_4 !!!!!!!!!!!!!!!!!
                                         */


                                        if ($this->check_step($step_5)) {
                                            foreach ($step_5 as $key => $value) {
                                                $value = (array)$value;
                                                DB::table('movables')->insertOrIgnore([
                                                    'ref_id' => $declaration->api_id,
                                                    'obj_id' => $key,
                                                    'person' => $this->check_and_get('person', $value),
                                                    'dateUse' => $this->check_and_get('dateUse', $value),
                                                    'acqPeriod' => $this->check_and_get('acqPeriod', $value),
                                                    'trademark' => $this->check_and_get('trademark', $value),
                                                    'objectType' => $this->check_and_get('objectType', $value),
                                                    'acqBeforeFD' => $this->check_and_get('acqBeforeFD', $value),
                                                    'costDateUse' => $this->check_and_get('costDateUse', $value),
                                                    'propertyDescr' => $this->check_and_get('propertyDescr', $value),
                                                    'otherObjectType' => $this->check_and_get('otherObjectType', $value),
                                                    'manufacturerName' => $this->check_and_get('manufacturerName', $value),
                                                    "created_at" => \Carbon\Carbon::now(),
                                                ]);


                                                foreach ($value as $key_value => $value_value) {


                                                    if ($key_value == 'rights') {
                                                        foreach ($value_value as $person => $data_rights) {
                                                            $data_rights = (array)$data_rights;
                                                            DB::table('rights')->insertOrIgnore([
                                                                'ref_id' => $declaration->api_id,
                                                                'type_id' => '5',
                                                                'obj_id' => $key,
                                                                'person_id' => $person,
                                                                'ownershipType' => $this->check_and_get('ownershipType', $data_rights),
                                                                'ua_lastname' => $this->check_and_get('ua_lastname', $data_rights),
                                                                'ua_firstname' => $this->check_and_get('ua_firstname', $data_rights),
                                                                'ua_middlename' => $this->check_and_get('ua_middlename', $data_rights),
                                                                'percentOwnership' => $this->check_and_get('percentOwnership', $data_rights),
                                                                'eng_company_code' => $this->check_and_get('eng_company_code', $data_rights),
                                                                'eng_company_name' => $this->check_and_get('eng_company_name', $data_rights),
                                                                'ukr_company_name' => $this->check_and_get('ukr_company_name', $data_rights),
                                                                'eng_company_address' => $this->check_and_get('eng_company_address', $data_rights),
                                                                'ukr_company_address' => $this->check_and_get('ukr_company_address', $data_rights),
                                                                "created_at" => \Carbon\Carbon::now(),
                                                            ]);
                                                            //   var_dump($data_rights->ownershipType);
                                                        }
                                                    }
                                                }
                                            }
                                            $this->info("Передвижное имущество добавлено[НЕ ТРАНСОРТНЫЕ СРЕДСТВА]");
                                        }
                                        if ($this->check_step($step_6)) {
                                            foreach ($step_6 as $key => $value) {
                                                $value = (array)$value;
                                                DB::table('vehicles')->insertOrIgnore([
                                                    'ref_id' => $declaration->api_id,
                                                    'obj_id' => $key,
                                                    'person' => $this->check_and_get('person', $value),
                                                    'brand' => $this->check_and_get('brand', $value),
                                                    'model' => $this->check_and_get('model', $value),
                                                    'costDate' => $this->check_and_get('costDate', $value),
                                                    'objectType' => $this->check_and_get('objectType', $value),
                                                    'owningDate' => $this->check_and_get('owningDate', $value),
                                                    'graduationYear' => $this->check_and_get('graduationYear', $value),
                                                    'otherObjectType' => $this->check_and_get('otherObjectType', $value),

                                                    "created_at" => \Carbon\Carbon::now(),
                                                ]);
                                                foreach ($value as $key_value => $value_value) {
                                                    if ($key_value == 'rights') {
                                                        foreach ($value_value as $person => $data_rights) {
                                                            $data_rights = (array)$data_rights;
                                                            DB::table('rights')->insertOrIgnore([
                                                                'ref_id' => $declaration->api_id,
                                                                'type_id' => '5',
                                                                'obj_id' => $key,
                                                                'person_id' => $person,
                                                                'ownershipType' => $this->check_and_get('ownershipType', $data_rights),
                                                                'ua_lastname' => $this->check_and_get('ua_lastname', $data_rights),
                                                                'ua_firstname' => $this->check_and_get('ua_firstname', $data_rights),
                                                                'ua_middlename' => $this->check_and_get('ua_middlename', $data_rights),
                                                                'percentOwnership' => $this->check_and_get('ownership', $data_rights),
                                                                'eng_company_code' => $this->check_and_get('eng_company_code', $data_rights),
                                                                'eng_company_name' => $this->check_and_get('eng_company_name', $data_rights),
                                                                'ukr_company_name' => $this->check_and_get('ukr_company_name', $data_rights),
                                                                'eng_company_address' => $this->check_and_get('eng_company_address', $data_rights),
                                                                'ukr_company_address' => $this->check_and_get('ukr_company_address', $data_rights),
                                                                "created_at" => \Carbon\Carbon::now(),
                                                            ]);
                                                            //   var_dump($data_rights->ownershipType);
                                                        }
                                                    }
                                                }
                                            }
                                            $this->info("Передвижное имущество добавлено[ТРАНСОРТНЫЕ СРЕДСТВА]");
                                        }
                                    } else {
                                        $this->info('ОШБИКА Изменения');
                                    }
                                }
                            } else {
                                $this->error('wrong region' . $this->check_and_get('region', $arr_step1));
                            }
                        }
                    } else {
                        $this->error('Что-то пошло не так');
                        var_dump($result);
                    }


                } else {
                    $this->info('Есть в базе');
                }
            }
            if ($i != 0) {
                $this->info($i . ' Успешно передано');
            } else {
                $this->info('Нечего передавать');
            }


        } else {
            $this->error('В базе данных нету данных');

        }


    }

    public function get_json($id)
    {
        $curl = curl_init();
        sleep(7);
        curl_setopt_array($curl, array(

            CURLOPT_URL => "https://public-api.nazk.gov.ua/v1/declaration/$id",
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

        if ($json) {
            if (array_key_exists('data', $json) and array_key_exists('schema_version', $json)) {
                if ($json->schema_version == '2' or $json->schema_version == '1' or $json->schema_version == '3') {
                    return [$json->data, $json->created_date];
                }
            }
        }
        return null;

    }

    public function check_step($step)
    {
        if ($step) {
            if (!array_key_exists('empty', $step)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function check_and_get($key, $arr)
    {
        if ($arr) {
            if (array_key_exists($key, $arr)) {
                return $arr[$key];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

}
