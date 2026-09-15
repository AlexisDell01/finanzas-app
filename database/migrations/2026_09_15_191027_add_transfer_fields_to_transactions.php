<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Cuenta a la que entra el dinero en una transferencia
            $table->foreignId('to_wallet_id')->nullable()->after('wallet_id')->constrained('wallets')->nullOnDelete();
        });
        
        // Si tu columna 'type' era un ENUM, necesitamos ampliarla para aceptar 'transfer'.
        // Si la creaste como string, esta línea de abajo no afectará.
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['to_wallet_id']);
            $table->dropColumn('to_wallet_id');
        });
    }
};