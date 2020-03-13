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
            $table->longText('description');
            $table->unsignedInteger('id_receipt');
            $table->foreign('id_receipt')->references('id')->on('receipts')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('id_account');
            $table->foreign('id_account')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('position',['Debit','Kredit']);
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
        Schema::dropIfExists('general_journals');
    }
}
