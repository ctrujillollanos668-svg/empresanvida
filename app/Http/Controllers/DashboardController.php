<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Si es invitado, mostrar la landing page pública (welcome.blade.php intacta)
        if (!$user) {
            $plans = Plan::where('status', true)->get();
            return view('welcome', compact('plans'));
        }

        // Si es administrador, redirigir a su panel de administración
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Si es cliente autenticado, cargar la app móvil VIP (cliente/dashboard.blade.php)
        $availablePlans = Plan::where('status', true)->get();

        $userPlans = $user->userPlans()
            ->with('plan')
            ->where('status', 'active')
            ->get();

        $referralsCount = $user->referrals()->count();
        $totalCommissions = $user->commissionsReceived()->sum('amount');
        $recentTransactions = $user->transactions()->latest()->take(5)->get();
        $rechargeBalance = $user->rechargeBalance();
        $earningsBalance = $user->earningsBalance();

        return view('cliente.dashboard', compact(
            'user',
            'availablePlans',
            'userPlans',
            'referralsCount',
            'totalCommissions',
            'recentTransactions',
            'rechargeBalance',
            'earningsBalance'
        ));
    }
}
