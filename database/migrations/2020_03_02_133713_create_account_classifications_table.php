<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_parent');
            $table->foreign('id_parent')->references('id')->on('account_parent');
            $table->string('classification_code');
            $table->string('classification_name');
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
        Schema::dropIfExists('account_classifications');
    }
}
