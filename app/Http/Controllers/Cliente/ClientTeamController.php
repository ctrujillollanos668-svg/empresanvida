<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientTeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Referidos directos (Nivel 1)
        $directReferrals = User::where('referred_by', $user->id)
            ->with(['userPlans.plan'])
            ->latest()
            ->get();

        // 2. Referidos de segundo nivel (Nivel 2)
        $directIds = $directReferrals->pluck('id')->toArray();
        $level2Referrals = User::whereIn('referred_by', $directIds)
            ->with(['userPlans.plan', 'sponsor'])
            ->latest()
            ->get();

        // 3. Comisiones totales ganadas
        $totalCommissions = $user->commissionsReceived()->sum('amount');
        $commissionHistory = $user->commissionsReceived()->with('downline')->latest()->paginate(10);

        return view('cliente.team.index', compact(
            'user',
            'directReferrals',
            'level2Referrals',
            'totalCommissions',
            'commissionHistory'
        ));
    }
}
