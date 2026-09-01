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
}
