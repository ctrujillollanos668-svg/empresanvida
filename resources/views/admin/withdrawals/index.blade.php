@extends('layouts.admin')

@section('title', 'Gestión de Retiros')

@section('content')
<div class="space-y-6">
    <!-- Encabezado y Filtros -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center gap-2">
                <span>📤</span> Solicitudes de Retiro
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-0.5">Revisa las cuentas/billeteras de los clientes y marca los pagos enviados.</p>
        </div>

        <!-- Filtros por Estado -->
        <div class="flex items-center gap-2 bg-slate-900 p-1.5 rounded-2xl border border-slate-800 text-xs">
            <a href="{{ route('admin.withdrawals.index') }}" class="px-3 py-1.5 rounded-xl font-semibold transition {{ empty($status) ? 'bg-cyan-500 text-slate-950' : 'text-slate-400 hover:text-white' }}">
                Todos
            </a>
            <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-xl font-semibold transition flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-400 hover:text-white' }}">
                <span>Pendientes</span>
                @if($pendingCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full bg-slate-950 text-white text-[10px]">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.withdrawals.index', ['status' => 'approved']) }}" class="px-3 py-1.5 rounded-xl font-semibold transition {{ $status === 'approved' ? 'bg-emerald-500 text-slate-950' : 'text-slate-400 hover:text-white' }}">
                Pagados
            </a>
            <a href="{{ route('admin.withdrawals.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-xl font-semibold transition {{ $status === 'rejected' ? 'bg-rose-500 text-white' : 'text-slate-400 hover:text-white' }}">
                Rechazados
            </a>
        </div>
    </div>

    <!-- Tabla de Retiros -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        @if($withdrawals->isEmpty())
            <div class="p-12 text-center text-slate-500 text-xs">
                <span class="text-3xl block mb-2">📭</span>
                No hay solicitudes de retiro en esta categoría.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 bg-slate-950/60 border-b border-slate-800">
                            <th class="py-3.5 px-4 font-semibold">ID</th>
                            <th class="py-3.5 px-4 font-semibold">Cliente</th>
                            <th class="py-3.5 px-4 font-semibold">Monto Solicitado</th>
                            <th class="py-3.5 px-4 font-semibold">Comisión / Neto a Enviar</th>
                            <th class="py-3.5 px-4 font-semibold">Billetera / Cuenta Destino</th>
                            <th class="py-3.5 px-4 font-semibold">Estado</th>
                            <th class="py-3.5 px-4 font-semibold">Fecha</th>
                            <th class="py-3.5 px-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($withdrawals as $with)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-4 px-4 font-mono text-slate-400">#{{ $with->id }}</td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-white">{{ $with->user->name ?? 'Usuario Eliminado' }}</div>
                                    <div class="text-slate-400 text-[11px]">{{ $with->user->email ?? '' }}</div>
                                </td>
                                <td class="py-4 px-4 font-mono text-slate-300 font-bold">
                                    ${{ number_format($with->amount, 0, ',', '.') }} <span class="text-[10px] text-slate-500 font-normal">COP</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-cyan-400 font-black font-mono text-sm">${{ number_format($with->net_amount, 0, ',', '.') }} COP</div>
                                    <div class="text-[10px] text-rose-400 font-mono font-semibold">Comisión (8%): -${{ number_format($with->fee, 0, ',', '.') }} COP</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-1.5 font-mono text-slate-200 bg-slate-950 px-2.5 py-1.5 rounded-lg border border-slate-800 text-[11px]">
                                        <span class="truncate max-w-[200px]" id="wallet-{{ $with->id }}">{{ $with->wallet_or_account }}</span>
                                        <button onclick="navigator.clipboard.writeText('{{ $with->wallet_or_account }}'); alert('¡Billetera copiada!')" title="Copiar billetera" class="text-cyan-400 hover:text-cyan-300 cursor-pointer">📋</button>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($with->status === 'pending')
                                        <span class="px-2.5 py-1 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-400 text-[10px] font-bold uppercase">Pendiente</span>
                                    @elseif($with->status === 'approved')
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase">Pagado</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-rose-500/15 border border-rose-500/30 text-rose-400 text-[10px] font-bold uppercase">Rechazado</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-slate-400 text-[11px]">
                                    {{ $with->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    @if($with->status === 'pending')
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Formulario Aprobar -->
                                            <form id="approve-with-{{ $with->id }}" method="POST" action="{{ route('admin.withdrawals.approve', $with->id) }}">
                                                @csrf
                                                <button type="button" onclick="confirmCustomAction({
                                                    title: '¿Confirmar Pago de Retiro?',
                                                    html: '¿Confirmas que ya realizaste la transferencia de <b class=\'text-cyan-400\'>${{ number_format($with->net_amount, 0, ',', '.') }} COP</b> a la cuenta <b class=\'text-white\'>{{ $with->wallet_or_account }}</b>?',
                                                    icon: 'question',
                                                    confirmText: '✓ Sí, Marcar como Pagado',
                                                    confirmColor: '#06b6d4',
                                                    formId: 'approve-with-{{ $with->id }}'
                                                })" class="px-3 py-1.5 bg-cyan-500 hover:bg-cyan-600 text-slate-950 font-black rounded-xl text-xs transition cursor-pointer shadow-md">
                                                    ✓ Marcar Pagado
                                                </button>
                                            </form>

                                            <!-- Formulario Rechazar (Devuelve Saldo) -->
                                            <form id="reject-with-{{ $with->id }}" method="POST" action="{{ route('admin.withdrawals.reject', $with->id) }}">
                                                @csrf
                                                <button type="button" onclick="confirmCustomAction({
                                                    title: '¿Rechazar y Reembolsar Retiro?',
                                                    html: 'El retiro por <b>${{ number_format($with->amount, 0, ',', '.') }} COP</b> será cancelado y el saldo se devolverá a la cuenta de <b>{{ $with->user->name }}</b>.',
                                                    icon: 'warning',
                                                    confirmText: '✕ Sí, Rechazar y Reembolsar',
                                                    confirmColor: '#f43f5e',
                                                    formId: 'reject-with-{{ $with->id }}'
                                                })" class="px-3 py-1.5 bg-rose-500/15 hover:bg-rose-500/25 border border-rose-500/30 text-rose-400 font-bold rounded-xl text-xs transition cursor-pointer">
                                                    ✕ Rechazar & Reembolsar
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-slate-500 text-[11px] italic">Procesado</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 border-t border-slate-800">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
