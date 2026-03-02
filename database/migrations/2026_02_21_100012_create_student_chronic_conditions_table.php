<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_chronic_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('chronic_condition_id')->constrained('chronic_conditions')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(
                ['student_id', 'chronic_condition_id'],
                'stud_chronic_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_chronic_conditions');
    }
};
