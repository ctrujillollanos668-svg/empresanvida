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
        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('downline_id')->constrained('users')->cascadeOnDelete();
            $table->integer('level')->default(1);
            $table->decimal('amount', 12, 2);
            $table->decimal('percentage', 5, 2);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
    }
};
