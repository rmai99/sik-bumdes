<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initial_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_account');
            $table->foreign('id_account')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('amount');
            $table->date('date');
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
        Schema::dropIfExists('initial_balances');
    }
}
