<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_images', function (Blueprint $table) {
            $table->id();
            $table->integer('note_id');
            $table->string('filename');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('note_id')->references('id')->on('notes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_images');
    }
};
