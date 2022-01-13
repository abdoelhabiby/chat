<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueToFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('friends', function (Blueprint $table) {
            $table->unique(['sender_id','reciver_id'],'unique_sender_id_reciver_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('friends', function (Blueprint $table) {
            $table->dropUnique('unique_sender_id_reciver_id');
        });
    }
}
