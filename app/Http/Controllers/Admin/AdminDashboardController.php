<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'cliente')->count();
        $totalDeposited = Deposit::where('status', 'approved')->sum('amount');
        $totalWithdrawn = Withdrawal::where('status', 'approved')->sum('amount');
        $pendingDeposits = Deposit::where('status', 'pending')->count();
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $activePlansCount = UserPlan::where('status', 'active')->count();

        $recentDeposits = Deposit::with('user')->latest()->take(5)->get();
        $recentWithdrawals = Withdrawal::with('user')->latest()->take(5)->get();
        $recentUsers = User::where('role', 'cliente')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalDeposited',
            'totalWithdrawn',
            'pendingDeposits',
            'pendingWithdrawals',
            'activePlansCount',
            'recentDeposits',
            'recentWithdrawals',
            'recentUsers'
        ));
    }

    /**
     * Endpoint liviano para consultar en segundo plano nuevas recargas o retiros pendientes (Compatible con cualquier Hosting)
     */
    public function checkNotifications()
    {
        $pendingDeposits = Deposit::where('status', 'pending')->count();
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();

        $latestDeposit = Deposit::with('user')
            ->where('status', 'pending')
            ->latest()
            ->first();

        $latestWithdrawal = Withdrawal::with('user')
            ->where('status', 'pending')
            ->latest()
            ->first();

        return response()->json([
            'pending_deposits' => $pendingDeposits,
            'pending_withdrawals' => $pendingWithdrawals,
            'latest_deposit' => $latestDeposit ? [
                'id' => $latestDeposit->id,
                'user_name' => $latestDeposit->user ? $latestDeposit->user->name : 'Cliente',
                'amount' => $latestDeposit->amount,
                'amount_formatted' => number_format($latestDeposit->amount, 0, ',', '.'),
                'payment_method' => $latestDeposit->payment_method,
                'created_at' => $latestDeposit->created_at ? $latestDeposit->created_at->diffForHumans() : 'Hace un momento',
            ] : null,
            'latest_withdrawal' => $latestWithdrawal ? [
                'id' => $latestWithdrawal->id,
                'user_name' => $latestWithdrawal->user ? $latestWithdrawal->user->name : 'Cliente',
                'amount' => $latestWithdrawal->amount,
                'amount_formatted' => number_format($latestWithdrawal->amount, 0, ',', '.'),
                'payment_method' => $latestWithdrawal->payment_method,
                'created_at' => $latestWithdrawal->created_at ? $latestWithdrawal->created_at->diffForHumans() : 'Hace un momento',
            ] : null,
        ]);
    }
}
