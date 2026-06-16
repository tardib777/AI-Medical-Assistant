<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->string('doctor_name');   // snapshot in case doctor deleted
            $table->date('date');
            $table->string('time');
            $table->string('spec')->nullable();
            $table->enum('status', ['confirmed', 'cancelled', 'completed', 'cancel_requested'])->default('confirmed');
            $table->text('cancel_reason')->nullable();
            $table->timestamp('booked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
