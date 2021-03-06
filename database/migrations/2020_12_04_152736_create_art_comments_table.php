<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_comments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('art');

            $table->text('text');

            $table->timestamps();

            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('art')->references('id')->on('art')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('art_comments');
    }
}
