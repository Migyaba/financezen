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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('initial_amount', 12, 2);
            $table->decimal('current_amount', 12, 2);
            $table->decimal('monthly_payment', 12, 2)->default(0);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->string('creditor')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['active', 'paid', 'paused'])->default('active');
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
