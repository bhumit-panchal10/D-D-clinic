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
    Schema::create('order_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        $table->foreignId('treatment_id')->constrained()->onDelete('cascade');
        $table->foreignId('patient_treatment_id')->constrained()->onDelete('cascade');
        $table->integer('qty');
        $table->decimal('rate', 10, 2);
        $table->decimal('amount', 10, 2);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('net_amount', 10, 2);
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
        Schema::dropIfExists('order_details');
    }
};
