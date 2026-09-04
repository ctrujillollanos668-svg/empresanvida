<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientWithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $withdrawals = $user->withdrawals()->latest()->paginate(10);
        $paymentMethods = PaymentMethod::where('status', true)->get();
        $withdrawableBalance = $user->withdrawableBalance();
        $uninvestedDeposit = $user->uninvestedDeposit();

        return view('cliente.withdrawals.index', compact('user', 'withdrawals', 'paymentMethods', 'withdrawableBalance', 'uninvestedDeposit'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $withdrawable = $user->withdrawableBalance();

        if ($withdrawable < 15000) {
            $uninvested = $user->uninvestedDeposit();
            $msg = $uninvested > 0
                ? 'No puedes retirar saldo de recargas directamente ($' . number_format($uninvested, 0, ',', '.') . ' COP). Este saldo debe ser invertido en Planes VIP para generar ganancias. Podrás retirar los rendimientos diarios y comisiones obtenidas una vez alcances el mínimo de $15.000 COP.'
                : 'Tu saldo retirable de ganancias actual es de $' . number_format($withdrawable, 0, ',', '.') . ' COP. El monto mínimo para retirar es de $15.000 COP.';

            return back()->with('error', $msg);
        }

        $request->validate([
            'amount' => 'required|numeric|min:15000|max:' . $withdrawable,
            'payment_method' => 'required|string',
            'wallet_or_account' => 'required|string|max:255',
        ], [
            'amount.min' => 'El monto mínimo de retiro es de $15.000 COP.',
            'amount.max' => 'El monto solicitado supera tu saldo retirable de ganancias ($' . number_format($withdrawable, 0, ',', '.') . ' COP). El saldo recargado debe ser utilizado para activar Planes VIP.',
            'wallet_or_account.required' => 'Ingresa tu número de cuenta, celular o billetera de destino.',
        ]);

        $amount = (float) $request->amount;
        $feePercentage = 8.00; // 8% de comisión por costo operativo y transferencia
        $fee = round(($amount * $feePercentage) / 100, 2);
        $netAmount = $amount - $fee;
        $destination = $request->payment_method . ' - ' . $request->wallet_or_account;

        DB::transaction(function () use ($user, $amount, $fee, $netAmount, $destination) {
            // 1. Descontar saldo del usuario
            $user->balance -= $amount;
            $user->save();

            // 2. Crear solicitud de retiro
            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'wallet_or_account' => $destination,
                'status' => 'pending',
                'admin_notes' => 'En proceso de pago.',
            ]);

            // 3. Registrar en transacciones
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => -$amount,
                'balance_after' => $user->balance,
                'description' => 'Solicitud de retiro a ' . $destination . ' (Comisión 8%: $' . number_format($fee, 0, ',', '.') . ' COP)',
            ]);
        });

        return redirect()->route('cliente.withdrawals.index')
            ->with('success', '¡Solicitud de retiro de $' . number_format($amount, 0, ',', '.') . ' COP enviada con éxito! Será transferida a tu cuenta en breve.');
    }
}
