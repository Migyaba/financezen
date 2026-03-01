<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer l'ancienne contrainte CHECK et en créer une nouvelle avec fedapay
        DB::statement("ALTER TABLE subscriptions DROP CONSTRAINT IF EXISTS subscriptions_payment_method_check");
        DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT subscriptions_payment_method_check CHECK (payment_method IN ('cinetpay', 'fedapay', 'manual', 'free'))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE subscriptions DROP CONSTRAINT IF EXISTS subscriptions_payment_method_check");
        DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT subscriptions_payment_method_check CHECK (payment_method IN ('cinetpay', 'manual', 'free'))");
    }
};
