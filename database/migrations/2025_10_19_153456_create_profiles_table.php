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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onUpdate('cascade');
            $table->enum('blood_type',['A+','A-','B+','B-','AB+','AB-','O+','O-']);
            $table->integer('height_cm');
            $table->float('weight_kg',1);
            $table->jsonb('chronic_diseases');
            $table->jsonb('allergies');
            $table->jsonb('current_medication');
            $table->jsonb('extra_info')->nullable();//يتضمن عمليات جراحية مسبقة أو حالات طارئة مسبقة والأدوية التي يتناولها المستخدم حاليا
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('profiles');
        Schema::enableForeignKeyConstraints();
    }
};
