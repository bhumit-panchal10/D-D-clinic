<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('patient_appointments', function (Blueprint $table) {
            $table->boolean('is_disrupted')->default(0)->after('status'); // 0 = Normal, 1 = Disrupted
        });
    }

    public function down()
    {
        Schema::table('patient_appointments', function (Blueprint $table) {
            $table->dropColumn('is_disrupted');
        });
    }
};
