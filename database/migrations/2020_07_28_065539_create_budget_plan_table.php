<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_budget_account');
            $table->foreign('id_budget_account')->references('id')->on('budget_account')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('budget_plan');
    }
}
