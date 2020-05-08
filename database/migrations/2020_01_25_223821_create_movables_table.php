<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ref_id');
            $table->string('person');
            $table->string('obj_id');
            $table->string('dateUse');
            $table->string('acqPeriod');
            $table->string('trademark');
            $table->string('objectType');
            $table->string('acqBeforeFD');
            $table->string('costDateUse');
            $table->string('propertyDescr');
            $table->string('otherObjectType');
            $table->string('manufacturerName');
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
        Schema::dropIfExists('movables');
    }
}
