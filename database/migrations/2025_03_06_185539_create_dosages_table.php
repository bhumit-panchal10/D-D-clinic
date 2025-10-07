<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDosagesTable extends Migration
{
    public function up()
    {
        Schema::create('dosages', function (Blueprint $table) {
            $table->id();
            $table->string('dosage');  // e.g., '1-1-0' (Morning, Afternoon, Evening)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dosages');
    }
}
