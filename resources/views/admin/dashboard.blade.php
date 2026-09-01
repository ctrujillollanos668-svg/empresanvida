@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Panel de Control General</h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-1">Monitoreo financiero en tiempo real, usuarios y solicitudes pendientes.</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Sistema En Línea
            </span>
        </div>
    </div>

    <!-- Tarjetas de Métricas Globales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Depositado -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 relative overflow-hidden">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Depositado</p>
            <h3 class="text-3xl font-extrabold text-emerald-400 mt-2 font-mono">${{ number_format($totalDeposited, 2) }}</h3>
            <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                <span>Ingreso neto aprobado</span>
                <span class="text-emerald-400 font-bold">USD</span>
            </div>
        </div>

        <!-- 2. Total Retirado -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 relative overflow-hidden">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Retirado</p>
            <h3 class="text-3xl font-extrabold text-cyan-400 mt-2 font-mono">${{ number_format($totalWithdrawn, 2) }}</h3>
            <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                <span>Pagos enviados</span>
                <span class="text-cyan-400 font-bold">USD</span>
            </div>
        </div>

        <!-- 3. Clientes Registrados -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 relative overflow-hidden">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Clientes</p>
            <h3 class="text-3xl font-extrabold text-white mt-2 font-mono">{{ $totalUsers }}</h3>
            <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                <span>Membresías activas: {{ $activePlansCount }}</span>
                <a href="{{ route('admin.users.index') }}" class="text-amber-400 hover:underline">Ver todos →</a>
            </div>
        </div>

        <!-- 4. Acciones Pendientes -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 relative overflow-hidden">
            <p class="text-xs font-bold text-amber-400 uppercase tracking-wider">Pendientes de Acción</p>
            <div class="mt-3 space-y-2 text-xs">
                <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}" class="flex justify-between items-center p-2 rounded-xl bg-slate-950/60 hover:bg-slate-800 border border-slate-800 transition">
                    <span class="text-slate-300">📥 Recargas por aprobar</span>
                    <strong class="text-emerald-400 font-mono text-sm">{{ $pendingDeposits }}</strong>
                </a>
                <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" class="flex justify-between items-center p-2 rounded-xl bg-slate-950/60 hover:bg-slate-800 border border-slate-800 transition">
                    <span class="text-slate-300">📤 Retiros por pagar</span>
                    <strong class="text-cyan-400 font-mono text-sm">{{ $pendingWithdrawals }}</strong>
                </a>
            </div>
        </div>
    </div>

    <!-- Secciones de Acciones y Tablas Rápidas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- 1. Depósitos Recientes -->
        <div class="bg-slate-900/70 border border-slate-800 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>📥</span> Últimas Recargas
                </h3>
                <a href="{{ route('admin.deposits.index') }}" class="text-xs font-semibold text-emerald-400 hover:underline">Ver todas →</a>
            </div>

            @if($recentDeposits->isEmpty())
                <p class="text-xs text-slate-500 py-8 text-center">No hay solicitudes de recarga registradas.</p>
            @else
                <div class="divide-y divide-slate-800/80">
                    @foreach($recentDeposits as $dep)
                        <div class="py-3.5 flex items-center justify-between text-xs">
                            <div>
                                <p class="font-bold text-white">{{ $dep->user->name ?? 'Usuario' }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $dep->payment_method }} • {{ $dep->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-emerald-400 font-mono text-sm">${{ number_format($dep->amount, 2) }}</span>
                                @if($dep->status === 'pending')
                                    <span class="block text-[10px] text-amber-400 font-bold uppercase">Pendiente</span>
                                @elseif($dep->status === 'approved')
                                    <span class="block text-[10px] text-emerald-400 font-bold uppercase">Aprobado</span>
                                @else
                                    <span class="block text-[10px] text-rose-400 font-bold uppercase">Rechazado</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 2. Retiros Recientes -->
        <div class="bg-slate-900/70 border border-slate-800 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>📤</span> Últimos Retiros
                </h3>
                <a href="{{ route('admin.withdrawals.index') }}" class="text-xs font-semibold text-cyan-400 hover:underline">Ver todos →</a>
            </div>

            @if($recentWithdrawals->isEmpty())
                <p class="text-xs text-slate-500 py-8 text-center">No hay solicitudes de retiro registradas.</p>
            @else
                <div class="divide-y divide-slate-800/80">
                    @foreach($recentWithdrawals as $with)
                        <div class="py-3.5 flex items-center justify-between text-xs">
                            <div>
                                <p class="font-bold text-white">{{ $with->user->name ?? 'Usuario' }}</p>
                                <p class="text-slate-400 text-[11px] font-mono truncate max-w-[180px]">{{ $with->wallet_or_account }}</p>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-cyan-400 font-mono text-sm">${{ number_format($with->net_amount, 2) }}</span>
                                @if($with->status === 'pending')
                                    <span class="block text-[10px] text-amber-400 font-bold uppercase">Pendiente</span>
                                @elseif($with->status === 'approved')
                                    <span class="block text-[10px] text-emerald-400 font-bold uppercase">Pagado</span>
                                @else
                                    <span class="block text-[10px] text-rose-400 font-bold uppercase">Rechazado</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
