<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBigDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('big_declarations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('api_id');
            $table->string('created_date');
            $table->integer('declarationType');
            $table->string('declarationYear1');
            $table->string('declarationYearTo');
            $table->string('declarationYearFrom');
            $table->string('declarationYear3');
            $table->string('declarationYear4');
            $table->string('lastName');
            $table->string('FirstName');
            $table->string('MiddleName');
            $table->string('cityType');
            $table->string('workPlace');
            $table->string('responsiblePosition');
            $table->string('corruptionAffected');
            $table->string('PostType');
            $table->string('workPost');
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
        Schema::dropIfExists('big_declarations');
    }
}
