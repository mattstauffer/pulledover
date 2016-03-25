<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBlacklistedToPhoneNumbersTableAndFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('phone_numbers', function (Blueprint $table) {
            $table->boolean('blacklisted')->after('is_verified')->default('0');
        });

        \Schema::table('friends', function (Blueprint $table) {
            $table->boolean('blacklisted')->after('is_verified')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        \Schema::table('phone_numbers', function (Blueprint $table) {
            $table->dropColumn('blacklisted');
        });

        \Schema::table('friends', function (Blueprint $table) {
            $table->dropColumn('blacklisted');
        });
    }
}
