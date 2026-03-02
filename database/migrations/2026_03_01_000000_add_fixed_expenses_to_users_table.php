<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('loyer', 12, 2)->default(0)->after('freelance_split');
            $table->decimal('eau_electricite', 12, 2)->default(0)->after('loyer');
            $table->decimal('internet', 12, 2)->default(0)->after('eau_electricite');
            $table->decimal('nourriture', 12, 2)->default(0)->after('internet');
            $table->decimal('essence', 12, 2)->default(0)->after('nourriture');
            $table->decimal('dette_initiale', 12, 2)->default(0)->nullable()->after('essence');
            $table->decimal('objectif_fonds_urgence', 12, 2)->default(500000)->nullable()->after('dette_initiale');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'loyer',
                'eau_electricite',
                'internet',
                'nourriture',
                'essence',
                'dette_initiale',
                'objectif_fonds_urgence'
            ]);
        });
    }
};
