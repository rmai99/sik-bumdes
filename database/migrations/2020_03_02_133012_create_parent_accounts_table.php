<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_parent', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_business')->nullable();
            $table->foreign('id_business')->references('id')->on('businesses')->onUpdate('cascade')->onDelete('set null');
            $table->integer('parent_code');
            $table->string('parent_name');
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
        Schema::dropIfExists('account_parent');
    }
}
