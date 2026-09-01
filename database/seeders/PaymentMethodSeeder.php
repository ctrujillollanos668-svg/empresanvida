<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (PaymentMethod::count() == 0) {
            PaymentMethod::create([
                'name' => 'Nequi',
                'type' => 'nequi',
                'account_number' => '3117944193',
                'account_holder' => 'Carlos Trujillo',
                'account_type' => 'Celular',
                'color_theme' => 'purple',
                'status' => true,
            ]);

            PaymentMethod::create([
                'name' => 'Daviplata',
                'type' => 'daviplata',
                'account_number' => '3109876543',
                'account_holder' => 'Administrador Daviplata',
                'account_type' => 'Celular',
                'color_theme' => 'rose',
                'status' => true,
            ]);

            PaymentMethod::create([
                'name' => 'Bancolombia',
                'type' => 'bancolombia',
                'account_number' => '123-456789-00',
                'account_holder' => 'Administrador Bancolombia',
                'account_type' => 'Ahorros',
                'color_theme' => 'amber',
                'status' => true,
            ]);

            PaymentMethod::create([
                'name' => 'Binance USDT',
                'type' => 'crypto',
                'account_number' => 'TX9d82u3J1k9Lp8z2AqX9012a8',
                'account_holder' => 'Billetera Crypto',
                'account_type' => 'TRC20',
                'color_theme' => 'emerald',
                'status' => true,
            ]);
        }
    }
}
