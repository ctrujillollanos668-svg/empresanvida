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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('daily_percentage', 5, 2); // ej. 5.00 para 5%
            $table->integer('duration_days')->default(30);
            $table->decimal('max_return', 12, 2); // monto o tope total
            $table->string('badge')->nullable(); // ej. 'Popular', 'VIP', 'Recomendado'
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
