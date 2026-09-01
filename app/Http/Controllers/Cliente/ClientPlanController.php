<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\ReferralCommission;
use App\Models\Transaction;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientPlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $availablePlans = Plan::where('status', true)->get();
        $activePlans = $user->userPlans()->with('plan')->where('status', 'active')->get();
        $completedPlans = $user->userPlans()->with('plan')->where('status', 'completed')->get();

        return view('cliente.plans.index', compact('user', 'availablePlans', 'activePlans', 'completedPlans'));
    }

    public function buy($id)
    {
        $plan = Plan::where('status', true)->findOrFail($id);
        $user = Auth::user();

        if ($user->balance < $plan->price) {
            return redirect()->route('cliente.deposits.index')
                ->with('error', 'Saldo insuficiente para activar este plan ($' . number_format($plan->price, 0, ',', '.') . ' COP). Por favor recarga saldo primero.');
        }

        DB::transaction(function () use ($user, $plan) {
            // 1. Descontar precio del plan del saldo del usuario
            $user->balance -= $plan->price;
            $user->save();

            // 2. Calcular rendimiento diario
            $dailyEarning = ($plan->price * $plan->daily_percentage) / 100;

            // 3. Crear el plan activo para el usuario (Inicia conteo de 24 horas)
            $userPlan = UserPlan::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'invested_amount' => $plan->price,
                'daily_earning' => $dailyEarning,
                'earned_so_far' => 0.00,
                'max_earning' => $plan->max_return,
                'last_claimed_at' => now(), // Cuenta regresiva de 24 horas inicia en el momento de la compra
                'status' => 'active',
            ]);

            // 4. Registrar transacción de compra
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'plan_purchase',
                'amount' => -$plan->price,
                'balance_after' => $user->balance,
                'description' => 'Activación de Membresía: ' . $plan->name,
            ]);

            // 5. Pagar comisión del 10% al patrocinador (Nivel 1)
            if ($user->referred_by) {
                $sponsor = $user->sponsor;
                if ($sponsor) {
                    $commissionAmount = $plan->price * 0.10; // 10% directo

                    $sponsor->balance += $commissionAmount;
                    $sponsor->save();

                    ReferralCommission::create([
                        'sponsor_id' => $sponsor->id,
                        'downline_id' => $user->id,
                        'level' => 1,
                        'amount' => $commissionAmount,
                        'percentage' => 10.00,
                        'description' => 'Comisión Nivel 1 por compra de ' . $plan->name . ' de ' . $user->name,
                    ]);

                    Transaction::create([
                        'user_id' => $sponsor->id,
                        'type' => 'referral_commission',
                        'amount' => $commissionAmount,
                        'balance_after' => $sponsor->balance,
                        'description' => 'Comisión de Referido Nivel 1 (10%) por ' . $user->name,
                    ]);
                }
            }
        });

        return redirect()->route('cliente.plans.index')
            ->with('success', '🎉 ¡Felicidades! Has activado el ' . $plan->name . '. Tu primer rendimiento de 24 horas ya comenzó a correr.');
    }

    public function claimDaily($id)
    {
        $user = Auth::user();
        $userPlan = UserPlan::where('user_id', $user->id)
            ->where('status', 'active')
            ->findOrFail($id);

        // Validar si ya transcurrieron exactamente las 24 horas
        if (!$userPlan->canClaim()) {
            $seconds = $userPlan->secondsUntilNextClaim();
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return back()->with('error', "⏳ Debes esperar exactamente 24 horas entre reclamos. Podrás reclamar nuevamente en {$hours}h {$minutes}m.");
        }

        DB::transaction(function () use ($user, $userPlan) {
            $earning = $userPlan->daily_earning;

            // Ajustar si el reclamo excede el tope máximo
            $remainingToMax = $userPlan->max_earning - $userPlan->earned_so_far;
            if ($earning > $remainingToMax) {
                $earning = $remainingToMax;
            }

            // 1. Sumar ganancia al balance del usuario
            $user->balance += $earning;
            $user->save();

            // 2. Actualizar el acumulado del plan y reiniciar contador de 24 horas
            $userPlan->earned_so_far += $earning;
            $userPlan->last_claimed_at = now();

            // 3. Si alcanzó el tope máximo, marcar como completado
            if ($userPlan->earned_so_far >= $userPlan->max_earning) {
                $userPlan->status = 'completed';
            }

            $userPlan->save();

            // 4. Registrar en libro contable
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'daily_claim',
                'amount' => $earning,
                'balance_after' => $user->balance,
                'description' => 'Rendimiento diario de ' . ($userPlan->plan->name ?? 'Plan VIP'),
            ]);
        });

        return back()->with('success', '💰 ¡Ganancia de $' . number_format($userPlan->daily_earning, 0, ',', '.') . ' COP acreditada a tu saldo disponible!');
    }
}
