<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ref_id');
            $table->string('obj_id');
            $table->string('person');
            $table->string('brand');
            $table->string('model');
            $table->string('costDate');
            $table->string('objectType');
            $table->string('owningDate');
            $table->string('graduationYear');
            $table->string('otherObjectType');
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
        Schema::dropIfExists('vehicles');
    }
}
