<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@plata.test',
            'phone' => '+57 300 1234567',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'balance' => 0.00,
            'referral_code' => 'ADMIN01',
            'status' => 'active',
        ]);

        // 2. Crear Cliente de Prueba
        $cliente = User::create([
            'name' => 'Juan Pérez',
            'email' => 'cliente@plata.test',
            'phone' => '+57 310 9876543',
            'password' => Hash::make('cliente123'),
            'role' => 'cliente',
            'balance' => 50000.00, // $50.000 COP
            'referral_code' => 'JUANVIP',
            'status' => 'active',
        ]);

        // 3. Crear Planes de Inversión en Pesos Colombianos (COP)
        Plan::create([
            'name' => 'VIP 1 - Bronce',
            'description' => 'Plan inicial accesible para comenzar. Rendimiento diario durante 30 días.',
            'price' => 30000.00, // $30.000 COP
            'daily_percentage' => 5.00, // 5% = $1.500 COP diarios
            'duration_days' => 30,
            'max_return' => 45000.00, // $45.000 COP total
            'badge' => 'Básico',
            'status' => true,
        ]);

        Plan::create([
            'name' => 'VIP 2 - Plata',
            'description' => 'Plan intermedio con mayor rentabilidad diaria garantizada.',
            'price' => 50000.00, // $50.000 COP
            'daily_percentage' => 6.00, // 6% = $3.000 COP diarios
            'duration_days' => 30,
            'max_return' => 90000.00, // $90.000 COP total
            'badge' => '🔥 Más Popular',
            'status' => true,
        ]);

        Plan::create([
            'name' => 'VIP 3 - Oro',
            'description' => 'Plan avanzado de alta rentabilidad y soporte prioritario en Telegram.',
            'price' => 100000.00, // $100.000 COP
            'daily_percentage' => 7.00, // 7% = $7.000 COP diarios
            'duration_days' => 30,
            'max_return' => 210000.00, // $210.000 COP total
            'badge' => '💎 Recomendado',
            'status' => true,
        ]);

        // 4. Métodos de Pago Dinámicos
        \App\Models\PaymentMethod::create([
            'name' => 'Nequi',
            'type' => 'nequi',
            'account_number' => '3115138588',
            'account_holder' => 'Carlos Trujillo',
            'account_type' => 'Celular',
            'color_theme' => 'purple',
            'status' => true,
        ]);

        \App\Models\PaymentMethod::create([
            'name' => 'Daviplata',
            'type' => 'daviplata',
            'account_number' => '3109876543',
            'account_holder' => 'Administrador Daviplata',
            'account_type' => 'Celular',
            'color_theme' => 'rose',
            'status' => true,
        ]);

        \App\Models\PaymentMethod::create([
            'name' => 'Bancolombia',
            'type' => 'bancolombia',
            'account_number' => '123-456789-00',
            'account_holder' => 'Administrador Bancolombia',
            'account_type' => 'Ahorros',
            'color_theme' => 'amber',
            'status' => true,
        ]);

        \App\Models\PaymentMethod::create([
            'name' => 'USDT (Binance)',
            'type' => 'crypto',
            'account_number' => 'TX9d82u3J1k9Lp8z2AqX9012a8',
            'account_holder' => 'Billetera Crypto',
            'account_type' => 'TRC20',
            'color_theme' => 'emerald',
            'status' => true,
        ]);
    }
}
