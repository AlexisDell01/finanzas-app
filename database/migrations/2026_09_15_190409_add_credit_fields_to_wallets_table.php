<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            // Agregamos el tipo (por defecto será estándar)
            $table->enum('type', ['standard', 'credit'])->default('standard')->after('name');
            // Agregamos el límite de crédito (puede ser nulo para efectivo/débito)
            $table->decimal('credit_limit', 12, 2)->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn(['type', 'credit_limit']);
        });
    }
};