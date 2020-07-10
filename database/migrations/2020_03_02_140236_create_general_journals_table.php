<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_journals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_detail');
            $table->foreign('id_detail')->references('id')->on('journal_detail')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('id_account');
            $table->foreign('id_account')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('position',['Debit','Kredit']);
            $table->integer('amount');
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
        Schema::dropIfExists('general_journals');
    }
}
