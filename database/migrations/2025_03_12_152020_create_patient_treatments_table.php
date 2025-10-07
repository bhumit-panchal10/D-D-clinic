<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patient_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('treatment_id')->constrained('treatments')->onDelete('cascade');
            $table->string('tooth_selection')->nullable(); // Comma-separated values
            $table->integer('is_billed')->default(0); // Changed to integer
            $table->integer('quotation_give')->default(0); // Changed to integer
            $table->decimal('rate', 8, 2);
            $table->integer('qty'); // Will be calculated from `tooth_selection`
            $table->decimal('amount', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_treatments');
    }
};
