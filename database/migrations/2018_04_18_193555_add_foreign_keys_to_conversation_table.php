<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToConversationTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversation', function (Blueprint $table) {
            $table->foreign('user_id', 'conversation_ibfk_1')->references('id')->on('telegram_users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('chat_id', 'conversation_ibfk_2')->references('id')->on('chats')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversation', function (Blueprint $table) {
            $table->dropForeign('conversation_ibfk_1');
            $table->dropForeign('conversation_ibfk_2');
        });
    }

}
