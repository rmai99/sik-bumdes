<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_session', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('id_business')->nullable();
            $table->foreign('id_business')->references('id')->on('businesses')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('session');
    }
}
