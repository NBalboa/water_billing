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
        Schema::create('consumers', function (Blueprint $table) {
            $table->id();
            $table->string('meter_code');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_no');
            $table->string('street');
            $table->string('barangay');
            $table->tinyInteger('is_deleted')->default(0); // 0 not deleted, 1 archived, 2 deleted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers');
    }
};
