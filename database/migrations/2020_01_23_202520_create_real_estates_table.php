<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealEstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('real_estates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ref_id');
            $table->string('obj_id');
            $table->string('person');
            $table->string('country');
            $table->string('costDate');
            $table->string('costAssessment');
            $table->string('objectType');
            $table->string('otherObjectType');
            $table->string('totalArea');
            $table->string('owningDate');
            $table->string('ua_cityType');
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
        Schema::dropIfExists('real_estates');
    }
}
