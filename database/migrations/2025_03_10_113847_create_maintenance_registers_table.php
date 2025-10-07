<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenance_registers', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->text('complain_details');
            $table->string('repair_person_name');
            $table->date('repair_given_date');
            $table->decimal('quotation_amount', 10, 2);
            $table->decimal('payment_paid_amount', 10, 2);
            $table->date('repair_received_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_registers');
    }
};
