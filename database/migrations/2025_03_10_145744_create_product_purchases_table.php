<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPurchasesTable extends Migration
{
    public function up()
    {
        Schema::create('product_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->integer('quantity')->unsigned();
            $table->decimal('rate', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->date('received_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_purchases');
    }
}
