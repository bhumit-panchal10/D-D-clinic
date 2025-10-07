<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('maintenance_registers', function (Blueprint $table) {
        $table->string('received_comment')->nullable()->after('repair_received_date');
    });
}

public function down()
{
    Schema::table('maintenance_registers', function (Blueprint $table) {
        $table->dropColumn('received_comment');
    });
}

};
