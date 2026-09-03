<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::where('role', 'cliente')
            ->with(['sponsor', 'userPlans.plan'])
            ->withCount('referrals')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15);
        $totalBalance = User::where('role', 'cliente')->sum('balance');

        return view('admin.users.index', compact('users', 'search', 'totalBalance'));
    }

    public function adjustBalance(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric',
            'action' => 'required|in:add,subtract',
            'reason' => 'required|string|max:255',
        ]);

        $amount = abs($request->amount);

        if ($request->action === 'subtract' && $user->balance < $amount) {
            return back()->with('error', 'El usuario no tiene suficiente saldo para descontar ese monto.');
        }

        if ($request->action === 'add') {
            $user->balance += $amount;
            $type = 'admin_credit';
            $desc = 'Ajuste manual (+): ' . $request->reason;
        } else {
            $user->balance -= $amount;
            $type = 'admin_debit';
            $desc = 'Ajuste manual (-): ' . $request->reason;
        }

        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $request->action === 'add' ? $amount : -$amount,
            'balance_after' => $user->balance,
            'description' => $desc,
        ]);

        return back()->with('success', 'Saldo del usuario actualizado correctamente.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->with('error', 'No puedes bloquear a un administrador.');
        }

        $user->status = $user->status === 'active' ? 'blocked' : 'active';
        $user->save();

        $state = $user->status === 'active' ? 'activada' : 'bloqueada';
        return back()->with('success', "La cuenta de {$user->name} ha sido {$state}.");
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return back()->with('success', "Nueva contraseña asignada a {$user->name} correctamente.");
    }
}
