<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('author');
            $table->string('title');
            $table->string('path');

            $table->set('status', ['pendent', 'accepted', 'rejected'])->default('pendent');

            $table->unsignedBigInteger('status_changed_by')
                    ->nullable();
                    
            $table->string('message_status')
                    ->nullable();

            $table->timestamps();

            $table->foreign('author')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arte');
    }
}
