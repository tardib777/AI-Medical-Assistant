<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('specialty');
            $table->string('specialty_en')->nullable();
            $table->float('rating', 2)->default(0);
            $table->boolean('available')->default(true);
            $table->string('image')->nullable();
            $table->string('experience')->nullable();
            $table->string('experience_en')->nullable();
            $table->text('bio')->nullable();
            $table->text('bio_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('telegram')->nullable();
            $table->string('email')->nullable();
            $table->string('hospital')->nullable();
            $table->string('hospital_en')->nullable();
            $table->text('education')->nullable();
            $table->string('languages')->nullable();
            $table->enum('status', ['verified', 'pending', 'suspended'])->default('pending');
            $table->json('available_dates')->nullable();
            $table->json('available_times')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
