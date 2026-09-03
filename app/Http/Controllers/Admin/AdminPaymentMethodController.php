<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->get();
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'account_number' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:100',
            'account_type' => 'nullable|string|max:50',
            'color_theme' => 'required|string|max:30',
            'qr_image' => 'nullable|image|max:8192',
        ]);

        $qrPath = null;
        if ($request->hasFile('qr_image')) {
            $qrPath = $request->file('qr_image')->store('qrs', 'public');
        }

        PaymentMethod::create([
            'name' => $request->name,
            'type' => $request->type,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'account_type' => $request->account_type,
            'qr_image' => $qrPath,
            'instructions' => $request->instructions,
            'color_theme' => $request->color_theme,
            'status' => true,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', '¡Método de pago "' . $request->name . '" creado exitosamente!');
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'account_number' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:100',
            'account_type' => 'nullable|string|max:50',
            'color_theme' => 'required|string|max:30',
            'qr_image' => 'nullable|image|max:8192',
        ]);

        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'account_type' => $request->account_type,
            'color_theme' => $request->color_theme,
            'instructions' => $request->instructions,
        ];

        if ($request->hasFile('qr_image')) {
            if ($method->qr_image && Storage::disk('public')->exists($method->qr_image)) {
                Storage::disk('public')->delete($method->qr_image);
            }
            $data['qr_image'] = $request->file('qr_image')->store('qrs', 'public');
        }

        $method->update($data);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', '¡Método de pago "' . $method->name . '" actualizado exitosamente!');
    }

    public function toggle($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->status = !$method->status;
        $method->save();

        $statusText = $method->status ? 'activado' : 'desactivado';
        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Método de pago "' . $method->name . '" ' . $statusText . ' para los clientes.');
    }

    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $name = $method->name;

        if ($method->qr_image && Storage::disk('public')->exists($method->qr_image)) {
            Storage::disk('public')->delete($method->qr_image);
        }

        $method->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', '¡Método de pago "' . $name . '" eliminado permanentemente!');
    }
}
