<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_changes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('art')->unique();

            $table->string('new_title');
            $table->string('new_image_path');

            $table->set('status', ['pendent', 'accepted', 'rejected'])->default('pendent');

            $table->unsignedBigInteger('status_changed_by')
                    ->nullable();
                    
            $table->string('message_status')
                    ->nullable();

            $table->timestamps();

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
        Schema::dropIfExists('art_changes');
    }
}
