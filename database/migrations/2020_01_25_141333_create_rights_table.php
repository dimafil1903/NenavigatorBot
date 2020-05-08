<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ref_id');
            $table->string('type_id');
            $table->string('obj_id');
            $table->string('person_id');
            $table->string('ownershipType');
            $table->string('percentOwnership');
            $table->string('ua_lastname');
            $table->string('ua_firstname');
            $table->string('ua_middlename');
            $table->string('eng_company_code');
            $table->string('eng_company_name');
            $table->string('ukr_company_name');
            $table->string('eng_company_address');
            $table->string('ukr_company_address');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rights');
    }
}
