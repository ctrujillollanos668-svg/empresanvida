@extends('layouts.admin')

@section('title', 'Cuentas de Pago y Códigos QR')

@section('content')
<div class="space-y-6">

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white flex items-center gap-2">
                <span>💳</span> Configuración de Cuentas y Códigos QR
            </h1>
            <p class="text-xs text-slate-400 mt-1">Sube tus códigos QR y números de cuenta. Estos son los datos que verán tus clientes cuando vayan a recargar saldo.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/15 border border-emerald-500/30 rounded-2xl text-emerald-300 text-xs sm:text-sm flex items-center gap-3 shadow-lg shadow-emerald-500/10">
            <span class="text-xl">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- 1. NEQUI -->
            <div class="bg-slate-900/90 border border-purple-500/30 rounded-3xl p-6 relative overflow-hidden shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="text-2xl">🟣</span>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Cuenta NEQUI</h3>
                            <span class="text-[11px] text-purple-400 font-semibold">QR y Transferencia Directa</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 text-[10px] font-bold">COLOMBIA</span>
                </div>

                <!-- Preview del QR actual -->
                <div class="flex flex-col sm:flex-row items-center gap-4 bg-slate-950 p-4 rounded-2xl border border-slate-800">
                    <div class="w-28 h-28 bg-slate-900 border border-purple-500/40 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($settings['nequi_qr'])
                            <img src="{{ asset('storage/' . $settings['nequi_qr']) }}" alt="QR Nequi" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-2 text-slate-500 text-[10px]">
                                <span class="text-2xl block mb-1">📷</span>
                                Sin QR subido
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 text-xs">
                        <label class="block font-semibold text-slate-300 mb-1">Subir / Cambiar Imagen del QR de Nequi</label>
                        <input type="file" name="nequi_qr" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-500/20 file:text-purple-300 hover:file:bg-purple-500/30 cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-1">Formato: PNG, JPG, JPEG (Máx 4MB)</p>
                    </div>
                </div>

                <!-- Campos de Nequi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Número de Celular Nequi</label>
                        <input type="text" name="nequi_number" value="{{ old('nequi_number', $settings['nequi_number']) }}" placeholder="300 1234567" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white font-mono focus:outline-none focus:border-purple-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Nombre del Titular</label>
                        <input type="text" name="nequi_holder" value="{{ old('nequi_holder', $settings['nequi_holder']) }}" placeholder="Ej: Carlos Trujillo" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-purple-500">
                    </div>
                </div>
            </div>

            <!-- 2. DAVIPLATA -->
            <div class="bg-slate-900/90 border border-rose-500/30 rounded-3xl p-6 relative overflow-hidden shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="text-2xl">🔴</span>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Cuenta DAVIPLATA</h3>
                            <span class="text-[11px] text-rose-400 font-semibold">QR y Transferencia Directa</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-300 text-[10px] font-bold">COLOMBIA</span>
                </div>

                <!-- Preview del QR actual -->
                <div class="flex flex-col sm:flex-row items-center gap-4 bg-slate-950 p-4 rounded-2xl border border-slate-800">
                    <div class="w-28 h-28 bg-slate-900 border border-rose-500/40 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($settings['daviplata_qr'])
                            <img src="{{ asset('storage/' . $settings['daviplata_qr']) }}" alt="QR Daviplata" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-2 text-slate-500 text-[10px]">
                                <span class="text-2xl block mb-1">📷</span>
                                Sin QR subido
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 text-xs">
                        <label class="block font-semibold text-slate-300 mb-1">Subir / Cambiar Imagen del QR Daviplata</label>
                        <input type="file" name="daviplata_qr" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-500/20 file:text-rose-300 hover:file:bg-rose-500/30 cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-1">Formato: PNG, JPG, JPEG (Máx 4MB)</p>
                    </div>
                </div>

                <!-- Campos de Daviplata -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Número de Celular Daviplata</label>
                        <input type="text" name="daviplata_number" value="{{ old('daviplata_number', $settings['daviplata_number']) }}" placeholder="310 9876543" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white font-mono focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Nombre del Titular</label>
                        <input type="text" name="daviplata_holder" value="{{ old('daviplata_holder', $settings['daviplata_holder']) }}" placeholder="Ej: Carlos Trujillo" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>
            </div>

            <!-- 3. BANCOLOMBIA -->
            <div class="bg-slate-900/90 border border-amber-500/30 rounded-3xl p-6 relative overflow-hidden shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="text-2xl">🟡</span>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Cuenta BANCOLOMBIA</h3>
                            <span class="text-[11px] text-amber-400 font-semibold">QR Bancolombia y Llave</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 text-[10px] font-bold">COLOMBIA</span>
                </div>

                <!-- Preview del QR actual -->
                <div class="flex flex-col sm:flex-row items-center gap-4 bg-slate-950 p-4 rounded-2xl border border-slate-800">
                    <div class="w-28 h-28 bg-slate-900 border border-amber-500/40 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($settings['bancolombia_qr'])
                            <img src="{{ asset('storage/' . $settings['bancolombia_qr']) }}" alt="QR Bancolombia" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-2 text-slate-500 text-[10px]">
                                <span class="text-2xl block mb-1">📷</span>
                                Sin QR subido
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 text-xs">
                        <label class="block font-semibold text-slate-300 mb-1">Subir / Cambiar Imagen del QR Bancolombia</label>
                        <input type="file" name="bancolombia_qr" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/30 cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-1">Formato: PNG, JPG, JPEG (Máx 4MB)</p>
                    </div>
                </div>

                <!-- Campos de Bancolombia -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-300 mb-1">Número de Cuenta</label>
                        <input type="text" name="bancolombia_account" value="{{ old('bancolombia_account', $settings['bancolombia_account']) }}" placeholder="123-456789-00" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white font-mono focus:outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Tipo</label>
                        <select name="bancolombia_type" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-amber-500">
                            <option value="Ahorros" {{ $settings['bancolombia_type'] == 'Ahorros' ? 'selected' : '' }}>Ahorros</option>
                            <option value="Corriente" {{ $settings['bancolombia_type'] == 'Corriente' ? 'selected' : '' }}>Corriente</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block font-semibold text-slate-300 mb-1">Nombre del Titular</label>
                        <input type="text" name="bancolombia_holder" value="{{ old('bancolombia_holder', $settings['bancolombia_holder']) }}" placeholder="Ej: Administrador VIP" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-amber-500">
                    </div>
                </div>
            </div>

            <!-- 4. USDT TRC20 -->
            <div class="bg-slate-900/90 border border-emerald-500/30 rounded-3xl p-6 relative overflow-hidden shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="text-2xl">🟢</span>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Billetera USDT</h3>
                            <span class="text-[11px] text-emerald-400 font-semibold">Criptomonedas / Binance</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">CRYPTO</span>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Dirección de Billetera USDT</label>
                        <input type="text" name="usdt_address" value="{{ old('usdt_address', $settings['usdt_address']) }}" placeholder="TX9d82u3J1k9Lp8z2AqX9012a8" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white font-mono focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Red (Network)</label>
                        <input type="text" name="usdt_network" value="{{ old('usdt_network', $settings['usdt_network']) }}" placeholder="TRC20" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white font-mono focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
            </div>

        </div>

        <!-- Botón Guardar Cambios -->
        <div class="text-right">
            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-slate-950 font-black rounded-2xl shadow-xl shadow-emerald-500/25 transition active:scale-95 text-sm cursor-pointer">
                💾 Guardar Todos los Cambios y Códigos QR
            </button>
        </div>

    </form>

</div>
@endsection
