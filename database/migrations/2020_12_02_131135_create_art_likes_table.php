<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_likes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('art');

            $table->timestamps();

            $table->foreign('user')->references('id')->on('users');
            $table->foreign('art')->references('id')->on('art');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('art_likes');
    }
}
