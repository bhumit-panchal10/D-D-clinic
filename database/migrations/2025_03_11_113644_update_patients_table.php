<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('patients'); // Drop the old table

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('mobile', 10);
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode', 6)->nullable();
            $table->string('reference_by')->nullable();
            $table->enum('is_government_employee', ['yes', 'no']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
