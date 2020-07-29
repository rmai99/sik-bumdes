<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->unsignedInteger('id_category')->nullable();
            $table->foreign('id_category')->references('id')->on('budget_account_category')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->unsignedInteger('id_company');
            $table->foreign('id_company')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('budget_account');
    }
}
