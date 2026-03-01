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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('salary_planned', 12, 2)->default(0);
            $table->decimal('salary_actual', 12, 2)->default(0);
            $table->decimal('freelance_planned', 12, 2)->default(0);
            $table->decimal('freelance_actual', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->unique(['user_id', 'month', 'year']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
