<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBitcoinFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->boolean('bitcoin_paid')
                  ->default(false)
                  ->after('remember_token');

            $table->integer('bitcoin_plan_id')
                  ->after('remember_token');
                  
            $table->string('bitcoin_address', 255)
                  ->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('bitcoin_paid');
            $table->dropColumn('bitcoin_plan_id');
            $table->dropColumn('bitcoin_address');
        });
    }
}
