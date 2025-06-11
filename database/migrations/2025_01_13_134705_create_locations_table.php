<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('location')->unique();//"50.258735, 28.603900"
            $table->unsignedBigInteger('id_type');
            $table->foreign('id_type')->references('id')->on('types');
            $table->unsignedBigInteger('id_category');
            $table->foreign('id_category')->references('id')->on('categories');
            $table->string('title');
            $table->string('description')->nullable();
            $table->text('photo_paths')->nullable();
            $table->dateTimeTz('start_time')->nullable();//'2023-11-22 13:37:00'
            $table->dateTimeTz('end_time')->nullable();//'2023-11-22 13:37:00'
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
        Schema::dropIfExists('locations');
    }
}
