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
        // Auto-migración segura para entornos serverless / Wasmer / Render
        try {
            if (!cache()->has('system_db_sync_v3')) {
                // 1. Columnas en users para Ruleta y Sobre Rojo
                if (Schema::hasTable('users')) {
                    if (!Schema::hasColumn('users', 'last_spin_at')) {
                        Schema::table('users', function (Blueprint $table) {
                            $table->timestamp('last_spin_at')->nullable()->after('status');
                        });
                    }
                    if (!Schema::hasColumn('users', 'claimed_red_packet')) {
                        Schema::table('users', function (Blueprint $table) {
                            $table->boolean('claimed_red_packet')->default(false)->after('status');
                        });
                    }
                    if (!Schema::hasColumn('users', 'roulette_spins')) {
                        Schema::table('users', function (Blueprint $table) {
                            $table->unsignedInteger('roulette_spins')->default(1)->after('status');
                        });
                    }
                }

                // 2. Columna stock en plans
                if (Schema::hasTable('plans') && !Schema::hasColumn('plans', 'stock')) {
                    Schema::table('plans', function (Blueprint $table) {
                        $table->integer('stock')->nullable()->after('max_return');
                    });
                }

                // 3. Ejecución segura de migraciones pendientes
                try {
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                } catch (\Throwable $migEx) {
                    // Ignorar si ya están sincronizadas
                }

                cache()->forever('system_db_sync_v3', true);
            }
        } catch (\Throwable $e) {
            // Ignorar silenciosamente si no hay conexión temporal o permisos DDL
        }
    }
}
