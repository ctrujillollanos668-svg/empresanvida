<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-migración segura para entornos serverless / Wasmer
        try {
            if (!cache()->has('plans_stock_column_checked')) {
                if (Schema::hasTable('plans') && !Schema::hasColumn('plans', 'stock')) {
                    Schema::table('plans', function (Blueprint $table) {
                        $table->integer('stock')->nullable()->after('max_return');
                    });
                }
                cache()->forever('plans_stock_column_checked', true);
            }
        } catch (\Throwable $e) {
            // Ignorar silenciosamente si ya existe o no hay permisos DDL
        }
    }
}
