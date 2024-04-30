<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdToMessagesTable extends Migration
{
    public function up()
    {
        Schema::connection('chat')->table('messages', function (Blueprint $table) {
            $table->id();
        });
    }

    public function down()
    {
        Schema::connection('chat')->table('messages', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}