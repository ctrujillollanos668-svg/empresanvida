<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    /**
     * Girar la Ruleta de la Suerte VIP (1 vez cada 24 horas)
     */
    public function spin(Request $request)
    {
        $user = Auth::user();

        if (($user->roulette_spins ?? 0) <= 0) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ No tienes giros disponibles. ¡Invita amigos con tu link de referido y cuando recarguen saldo ganarás +2 giros gratis por cada uno!',
                'spins_left' => 0,
            ], 422);
        }

        // Segmentos de la ruleta con premios elevados en COP
        $segments = [
            ['index' => 0, 'prize' => 1000,  'label' => '$1.000 COP',  'color' => '#10b981'],
            ['index' => 1, 'prize' => 2000,  'label' => '$2.000 COP',  'color' => '#06b6d4'],
            ['index' => 2, 'prize' => 5000,  'label' => '$5.000 COP',  'color' => '#f59e0b'],
            ['index' => 3, 'prize' => 9000,  'label' => '$9.000 COP', 'color' => '#ec4899'],
            ['index' => 4, 'prize' => 500,   'label' => '$500 COP',    'color' => '#8b5cf6'],
            ['index' => 5, 'prize' => 13000, 'label' => '👑 $13.000',  'color' => '#ef4444'],
            ['index' => 6, 'prize' => 3000,  'label' => '$3.000 COP',  'color' => '#14b8a6'],
            ['index' => 7, 'prize' => 1000,  'label' => '$1.000 COP',  'color' => '#3b82f6'],
        ];

        // Contar cuántos giros ha realizado este usuario en total
        $previousSpinsCount = Transaction::where('user_id', $user->id)
            ->where('type', 'roulette_reward')
            ->count();

        // LÓGICA DE SECUENCIA SOLICITADA:
        // Giro #1: Cae en $1.000 COP
        // Giro #2: Cae en $1.000 COP
        // Giro #3 en adelante: Totalmente ALEATORIO de todos los premios ($500, $2.000, $3.000, $5.000, $10.000, $20.000)
        if ($previousSpinsCount === 0) {
            $selected = $segments[0]; // $1.000 COP
        } elseif ($previousSpinsCount === 1) {
            $selected = $segments[7]; // $1.000 COP
        } else {
            // A partir del 3er giro: Totalmente aleatorio de toda la rueda
            $selected = $segments[array_rand($segments)];
        }

        $prize = $selected['prize'];

        DB::transaction(function () use ($user, $prize) {
            $user->roulette_spins = max(0, $user->roulette_spins - 1);
            $user->balance += $prize;
            $user->last_spin_at = now();
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'roulette_reward',
                'amount' => $prize,
                'balance_after' => $user->balance,
                'description' => 'Premio de Ruleta de la Suerte (+' . number_format($prize, 0, ',', '.') . ' COP)',
            ]);
        });

        return response()->json([
            'success' => true,
            'segment_index' => $selected['index'],
            'prize' => $prize,
            'prize_label' => $selected['label'],
            'spins_left' => $user->roulette_spins,
            'new_balance' => $user->balance,
            'new_balance_formatted' => '$' . number_format($user->balance, 0, ',', '.') . ' COP',
        ]);
    }

    /**
     * Abrir Sobre Rojo VIP o Canjear Código Promocional
     */
    public function claimRedPacket(Request $request)
    {
        $user = Auth::user();
        $code = strtoupper(trim($request->input('code', '')));

        // 1. Canje con código promocional exclusivo (ej: VIP2026, BONO777, PYRAMID, PLATA)
        if (!empty($code)) {
            $validPromoCodes = [
                'VIP2026' => 5000,
                'BONO777' => 3000,
                'NVIDA' => 2500,
                'NVIDIA' => 2500,
                'PYRAMID' => 2500,
                'PLATA' => 2000,
                'NEQUI' => 1500,
            ];

            if (!isset($validPromoCodes[$code])) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ El código promocional ingresado no es válido o ya caducó.',
                ], 422);
            }

            // Validar si ya canjeó este código
            $alreadyUsed = Transaction::where('user_id', $user->id)
                ->where('description', 'LIKE', "%Código Promocional [{$code}]%")
                ->exists();

            if ($alreadyUsed) {
                return response()->json([
                    'success' => false,
                    'message' => "⚠️ Ya canjeaste el código {$code} anteriormente.",
                ], 422);
            }

            $prize = $validPromoCodes[$code];

            DB::transaction(function () use ($user, $prize, $code) {
                $user->balance += $prize;
                $user->save();

                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'promo_code',
                    'amount' => $prize,
                    'balance_after' => $user->balance,
                    'description' => "Bono de Código Promocional [{$code}] (+" . number_format($prize, 0, ',', '.') . " COP)",
                ]);
            });

            return response()->json([
                'success' => true,
                'prize' => $prize,
                'message' => "🎉 ¡Código {$code} canjeado con éxito! Recibes +$" . number_format($prize, 0, ',', '.') . " COP en tu saldo disponible.",
                'new_balance' => $user->balance,
                'new_balance_formatted' => '$' . number_format($user->balance, 0, ',', '.') . ' COP',
            ]);
        }

        // 2. Sobre Rojo de Bienvenida para Nuevos Usuarios (1 vez por cuenta)
        if ($user->claimed_red_packet) {
            return response()->json([
                'success' => false,
                'message' => '🧧 Ya abriste tu Sobre Rojo de Bienvenida. ¡Ingresa un código promocional de Telegram para más bonos!',
            ], 422);
        }

        // Bono de bienvenida sorpresa
        $welcomePrizes = [2000, 2500, 3000, 5000];
        $prize = $welcomePrizes[array_rand($welcomePrizes)];

        DB::transaction(function () use ($user, $prize) {
            $user->balance += $prize;
            $user->claimed_red_packet = true;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'welcome_bonus',
                'amount' => $prize,
                'balance_after' => $user->balance,
                'description' => 'Bono de Bienvenida Sobre Rojo (+' . number_format($prize, 0, ',', '.') . ' COP)',
            ]);
        });

        return response()->json([
            'success' => true,
            'prize' => $prize,
            'message' => "🧧 ¡Sobre Rojo abierto! Has ganado un bono de bienvenida de +$" . number_format($prize, 0, ',', '.') . " COP acreditado a tu balance.",
            'new_balance' => $user->balance,
            'new_balance_formatted' => '$' . number_format($user->balance, 0, ',', '.') . ' COP',
        ]);
    }
}
