<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFridgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fridges', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name');
            $table->foreignId('location_id')->unique()->unsigned();                //     when errors with migration occurs
            $table->foreignId('mode_id')->default(3)->unsigned();          //           check there
            $table->string('tfid', 64)->unique()->nullable();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('mode_id')->references('id')->on('modes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fridges');
    }
}
