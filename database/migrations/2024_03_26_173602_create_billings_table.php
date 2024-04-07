<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->constrained()->onDelete('cascade');
            $table->foreignId('collector_id');
            $table->dateTime('reading_date');
            $table->dateTime('due_date');
            $table->integer('previos');
            $table->integer('current');
            $table->integer('total_consumption');
            $table->string('status'); //PENDING, //PAID
            $table->decimal('price'); //Water Bill
            $table->decimal('total'); //Grand Total
            $table->decimal('after_due');
            $table->tinyInteger('is_deleted')->default(0); // 0 not deleted, 1 archived, 2 deleted
            $table->dateTime('paid_at')->nullable();
            $table->decimal('change')->default(0);
            $table->decimal('money')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
