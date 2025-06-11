<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('private');
            $table->unsignedBigInteger('location_id')->nullable(); // location_id can be null
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->CascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
