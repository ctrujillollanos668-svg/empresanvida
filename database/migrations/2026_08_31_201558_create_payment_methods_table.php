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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ej: 'Nequi', 'Daviplata', 'Bancolombia Ahorros', 'Binance USDT'
            $table->string('type')->default('nequi'); // 'nequi', 'daviplata', 'bancolombia', 'crypto', 'other'
            $table->string('account_number'); // ej: '3117944193' o billetera
            $table->string('account_holder')->nullable(); // ej: 'Carlos Trujillo'
            $table->string('account_type')->nullable(); // ej: 'Ahorros', 'Corriente', 'Celular', 'TRC20'
            $table->string('qr_image')->nullable(); // ruta a la imagen del QR subida
            $table->text('instructions')->nullable();
            $table->string('color_theme')->default('purple'); // 'purple', 'rose', 'amber', 'emerald', 'blue'
            $table->boolean('status')->default(true); // activo / inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
