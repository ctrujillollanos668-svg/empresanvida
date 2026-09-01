<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'nequi_number' => Setting::get('nequi_number', '3001234567'),
            'nequi_holder' => Setting::get('nequi_holder', 'Administrador Nequi'),
            'nequi_qr' => Setting::get('nequi_qr', null),

            'daviplata_number' => Setting::get('daviplata_number', '3109876543'),
            'daviplata_holder' => Setting::get('daviplata_holder', 'Administrador Daviplata'),
            'daviplata_qr' => Setting::get('daviplata_qr', null),

            'bancolombia_account' => Setting::get('bancolombia_account', '123-456789-00'),
            'bancolombia_holder' => Setting::get('bancolombia_holder', 'Administrador Bancolombia'),
            'bancolombia_type' => Setting::get('bancolombia_type', 'Ahorros'),
            'bancolombia_qr' => Setting::get('bancolombia_qr', null),

            'usdt_address' => Setting::get('usdt_address', 'TX9d82u3J1k9Lp8z2AqX9012a8'),
            'usdt_network' => Setting::get('usdt_network', 'TRC20'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nequi_number' => 'nullable|string|max:50',
            'nequi_holder' => 'nullable|string|max:100',
            'nequi_qr' => 'nullable|image|max:4096',

            'daviplata_number' => 'nullable|string|max:50',
            'daviplata_holder' => 'nullable|string|max:100',
            'daviplata_qr' => 'nullable|image|max:4096',

            'bancolombia_account' => 'nullable|string|max:50',
            'bancolombia_holder' => 'nullable|string|max:100',
            'bancolombia_type' => 'nullable|string|max:50',
            'bancolombia_qr' => 'nullable|image|max:4096',

            'usdt_address' => 'nullable|string|max:255',
            'usdt_network' => 'nullable|string|max:50',
        ]);

        // Guardar textos
        $textFields = [
            'nequi_number', 'nequi_holder',
            'daviplata_number', 'daviplata_holder',
            'bancolombia_account', 'bancolombia_holder', 'bancolombia_type',
            'usdt_address', 'usdt_network',
        ];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field), 'text');
            }
        }

        // Guardar imágenes de QR
        $imageFields = ['nequi_qr', 'daviplata_qr', 'bancolombia_qr'];

        foreach ($imageFields as $imgField) {
            if ($request->hasFile($imgField)) {
                $path = $request->file($imgField)->store('qrs', 'public');
                Setting::set($imgField, $path, 'image');
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', '¡Cuentas bancarias y códigos QR de recarga actualizados correctamente!');
    }
}
