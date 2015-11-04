<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recordings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('from');
            $table->string('city');
            $table->string('state');
            $table->string('url');
            $table->string('recording_sid');
            $table->integer('duration');
            $table->text('json');
            $table->integer('user_id')->unsigned()->references('id')->on('users');
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
        Schema::drop('recordings');
    }
}
