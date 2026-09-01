<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Withdrawal::with('user')->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $withdrawals = $query->paginate(15);
        $pendingCount = Withdrawal::where('status', 'pending')->count();

        return view('admin.withdrawals.index', compact('withdrawals', 'status', 'pendingCount'));
    }

    public function approve($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Esta solicitud de retiro ya fue procesada.');
        }

        $withdrawal->update([
            'status' => 'approved',
            'admin_notes' => 'Transferencia completada el ' . now()->format('d/m/Y H:i'),
        ]);

        return back()->with('success', '¡Retiro de $' . number_format($withdrawal->net_amount, 2) . ' marcado como pagado exitosamente!');
    }

    public function reject(Request $request, $id)
    {
        $withdrawal = Withdrawal::with('user')->findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Esta solicitud de retiro ya fue procesada.');
        }

        DB::transaction(function () use ($withdrawal, $request) {
            $user = $withdrawal->user;

            // 1. Devolver saldo al cliente
            $user->balance += $withdrawal->amount;
            $user->save();

            // 2. Marcar retiro como rechazado
            $withdrawal->update([
                'status' => 'rejected',
                'admin_notes' => $request->input('admin_notes', 'Dirección de billetera incorrecta o solicitud inválida.'),
            ]);

            // 3. Registrar reembolso en historial
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal_refund',
                'amount' => $withdrawal->amount,
                'balance_after' => $user->balance,
                'description' => 'Reembolso por solicitud de retiro rechazada',
            ]);
        });

        return back()->with('success', 'El retiro fue rechazado y el saldo devuelto a la cuenta del usuario.');
    }
}
