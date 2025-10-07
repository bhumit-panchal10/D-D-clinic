<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePatientAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::table('patient_appointments', function (Blueprint $table) {
            $table->dropColumn('attendance'); // Drop the attendance column
            $table->tinyInteger('status')->default(0); // Add status column with default value 0 (0 = No, 1 = Yes)
        });
    }

    public function down()
    {
        Schema::table('patient_appointments', function (Blueprint $table) {
            $table->string('attendance')->nullable(); // Re-add attendance if rollback
            $table->dropColumn('status'); // Remove status if rollback
        });
    }
}
