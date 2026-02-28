<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_dietaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('dietary_id')->constrained('dietaries')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['student_id', 'dietary_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_dietaries');
    }
};
