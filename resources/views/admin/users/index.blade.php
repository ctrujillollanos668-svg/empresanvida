@extends('layouts.admin')

@section('title', 'Gestión de Clientes y Red')

@section('content')
<div class="space-y-6">
    <!-- Encabezado y Barra de Búsqueda -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center gap-2">
                <span>👥</span> Gestión de Clientes y Red
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-0.5">Controla saldos, estados de cuenta, árbol de referidos y bloqueos.</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-slate-900 px-4 py-2 rounded-2xl border border-slate-800 text-xs">
                <span class="text-slate-400">Saldo Total en Manos de Clientes:</span>
                <strong class="text-emerald-400 font-mono ml-1 text-sm">${{ number_format($totalBalance, 2) }} USD</strong>
            </div>
        </div>
    </div>

    <!-- Buscador -->
    <div class="bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, correo o código de referido..." class="flex-1 px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white text-xs focus:outline-none focus:border-amber-500">
            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition cursor-pointer">
                🔍 Buscar
            </button>
            @if($search)
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-800 text-slate-300 rounded-xl text-xs flex items-center">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Tabla de Clientes -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        @if($users->isEmpty())
            <div class="p-12 text-center text-slate-500 text-xs">
                <span class="text-3xl block mb-2">👤</span>
                No se encontraron clientes registrados con ese criterio.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 bg-slate-950/60 border-b border-slate-800">
                            <th class="py-3.5 px-4 font-semibold">Cliente</th>
                            <th class="py-3.5 px-4 font-semibold">Código Propio</th>
                            <th class="py-3.5 px-4 font-semibold">Patrocinador</th>
                            <th class="py-3.5 px-4 font-semibold">Saldo Disponible</th>
                            <th class="py-3.5 px-4 font-semibold">Referidos</th>
                            <th class="py-3.5 px-4 font-semibold">Estado</th>
                            <th class="py-3.5 px-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-4 px-4">
                                    <div class="font-bold text-white">{{ $u->name }}</div>
                                    <div class="text-slate-400 text-[11px]">{{ $u->email }}</div>
                                    @if($u->phone)
                                        <div class="text-slate-500 text-[10px]">{{ $u->phone }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-mono text-emerald-400 font-bold bg-slate-950 px-2.5 py-1 rounded-lg border border-slate-800">{{ $u->referral_code }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    @if($u->sponsor)
                                        <span class="text-cyan-400 font-semibold">{{ $u->sponsor->name }}</span>
                                        <span class="block text-[10px] text-slate-500 font-mono">{{ $u->sponsor->referral_code }}</span>
                                    @else
                                        <span class="text-slate-500 italic">Sin patrocinador</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-emerald-400 font-mono font-extrabold text-sm">${{ number_format($u->balance, 2) }}</span>
                                </td>
                                <td class="py-4 px-4 font-mono text-slate-300">
                                    {{ $u->referrals_count }} directos
                                </td>
                                <td class="py-4 px-4">
                                    @if($u->status === 'active')
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase">Activo</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-rose-500/15 border border-rose-500/30 text-rose-400 text-[10px] font-bold uppercase">Bloqueado</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Botón Ajustar Saldo -->
                                        <button onclick="openBalanceModal({{ json_encode($u) }})" class="px-3 py-1.5 bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/30 text-amber-400 font-bold rounded-xl text-xs transition cursor-pointer">
                                            💵 Ajustar Saldo
                                        </button>

                                        <!-- Botón Cambiar Contraseña -->
                                        <button onclick="openPasswordModal({{ json_encode($u) }})" class="px-3 py-1.5 bg-cyan-500/15 hover:bg-cyan-500/25 border border-cyan-500/30 text-cyan-400 font-bold rounded-xl text-xs transition cursor-pointer" title="Asignar nueva clave">
                                            🔑 Clave
                                        </button>

                                        <!-- Botón Bloquear/Desbloquear -->
                                        <form method="POST" action="{{ route('admin.users.toggleStatus', $u->id) }}">
                                            @csrf
                                            <button type="submit" onclick="return confirm('¿Cambiar estado de la cuenta de {{ $u->name }}?')" class="px-3 py-1.5 {{ $u->status === 'active' ? 'bg-rose-500/15 text-rose-400 border border-rose-500/30 hover:bg-rose-500/25' : 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/25' }} font-bold rounded-xl text-xs transition cursor-pointer">
                                                {{ $u->status === 'active' ? '🚫 Bloquear' : '✓ Desbloquear' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 border-t border-slate-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- MODAL AJUSTAR SALDO -->
<div id="balanceModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white">Ajustar Saldo Manual</h3>
            <button onclick="document.getElementById('balanceModal').classList.add('hidden')" class="text-slate-400 hover:text-white text-xl">✕</button>
        </div>

        <p class="text-xs text-slate-400 mb-4">Cliente: <strong id="modalUserName" class="text-white"></strong> (Saldo actual: <span id="modalUserBalance" class="text-emerald-400 font-mono"></span>)</p>

        <form id="balanceForm" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-300 mb-1">Tipo de Ajuste</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="p-3 bg-slate-950 border border-slate-800 rounded-xl flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="action" value="add" checked class="text-emerald-500">
                        <span class="text-emerald-400 font-bold">➕ Agregar Saldo</span>
                    </label>
                    <label class="p-3 bg-slate-950 border border-slate-800 rounded-xl flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="action" value="subtract" class="text-rose-500">
                        <span class="text-rose-400 font-bold">➖ Restar Saldo</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1">Monto en Pesos ($ COP)</label>
                <input type="number" step="any" min="1" name="amount" required placeholder="Ej: 50000" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white font-mono text-sm focus:outline-none focus:border-amber-500">
                <span class="text-[10px] text-slate-500 mt-0.5 block">Ej: 50000 = $50.000 COP</span>
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1">Motivo / Razón del Ajuste</label>
                <input type="text" name="reason" required placeholder="Ej: Bono de bienvenida, corrección, premio especial..." class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-amber-500">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('balanceModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-semibold cursor-pointer">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-amber-500 text-slate-950 font-bold rounded-xl shadow-lg cursor-pointer">Aplicar Ajuste</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Asignar / Cambiar Contraseña -->
<div id="passwordModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <span>🔑</span> Cambiar Contraseña
            </h3>
            <button type="button" onclick="document.getElementById('passwordModal').classList.add('hidden')" class="text-slate-400 hover:text-white text-sm">✕</button>
        </div>
        <p class="text-xs text-slate-400 mb-4">
            Cliente: <strong id="pwdModalUserName" class="text-cyan-400"></strong><br>
            Contacto: <span id="pwdModalUserPhone" class="text-slate-300 font-mono"></span>
        </p>

        <form id="passwordForm" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-300 mb-1.5">Nueva Contraseña para el Usuario</label>
                <div class="flex gap-2">
                    <input type="text" id="newPasswordInput" name="password" required minlength="6" placeholder="Ej: Fortex2026*" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white font-mono text-sm focus:outline-none focus:border-cyan-500">
                    <button type="button" onclick="generateQuickPassword()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-cyan-400 border border-slate-700 rounded-xl font-bold whitespace-nowrap" title="Generar clave aleatoria">
                        🎲 Generar
                    </button>
                </div>
                <span class="text-[10px] text-slate-500 mt-1 block">Mínimo 6 caracteres. Puedes escribir una nueva o presionar «Generar».</span>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('passwordModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-semibold cursor-pointer">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-cyan-500 text-slate-950 font-bold rounded-xl shadow-lg cursor-pointer">Guardar Clave</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBalanceModal(user) {
        document.getElementById('balanceForm').action = '/admin/users/' + user.id + '/adjust-balance';
        document.getElementById('modalUserName').innerText = user.name;
        document.getElementById('modalUserBalance').innerText = '$' + Math.round(parseFloat(user.balance)).toLocaleString('es-CO') + ' COP';
        document.getElementById('balanceModal').classList.remove('hidden');
    }

    function openPasswordModal(user) {
        document.getElementById('passwordForm').action = '/admin/users/' + user.id + '/password';
        document.getElementById('pwdModalUserName').innerText = user.name;
        document.getElementById('pwdModalUserPhone').innerText = (user.phone ? '📞 ' + user.phone + ' | ' : '') + '✉️ ' + user.email;
        document.getElementById('newPasswordInput').value = '';
        document.getElementById('passwordModal').classList.remove('hidden');
    }

    function generateQuickPassword() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        let pass = 'Fortex';
        for (let i = 0; i < 4; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        pass += '*';
        document.getElementById('newPasswordInput').value = pass;
    }
</script>
@endsection
