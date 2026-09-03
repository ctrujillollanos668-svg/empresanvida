@extends('layouts.cliente')

@section('title', 'App VIP')

@section('content')
<div class="max-w-lg mx-auto space-y-4 pb-12">

    <!-- 1. ENCABEZADO DE LA APP: LOGO + SELECTOR + SALDO RÁPIDO -->
    <div class="flex items-center justify-between py-1 px-1">
        <div class="flex items-center gap-2.5">
            <div class="w-10 h-10 rounded-2xl bg-black border border-emerald-500/30 flex items-center justify-center overflow-hidden shadow-lg shadow-emerald-500/25">
                <img src="{{ asset('img/fortex.jpg') }}" alt="FORTEX" class="w-full h-full object-cover">
            </div>
            <div>
                <h1 class="text-base font-black text-white tracking-tight leading-none">FORTEX</h1>
                <span class="text-[9px] text-emerald-400 font-bold uppercase tracking-wider flex items-center gap-1 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Servidores Cloud Verificados
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Chip Idioma / Moneda -->
            <div class="flex items-center gap-1 px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-xl text-[11px] font-bold text-slate-300">
                <span>🇨🇴</span>
                <span>COP</span>
            </div>

            <!-- Soporte Flotante Directo -->
            <button onclick="openSupportModal()" class="w-8 h-8 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 flex items-center justify-center text-slate-300 hover:text-emerald-400 transition cursor-pointer" title="Centro de Ayuda">
                🎧
            </button>
        </div>
    </div>

    <!-- 2. CARRUSEL / BANNER PROMOCIONAL CORPORATIVO (ESTILO APP NATIVA) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-950 via-slate-900 to-cyan-950 border border-emerald-500/30 shadow-2xl p-5 min-h-[160px] flex flex-col justify-between">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-extrabold border border-emerald-500/30 mb-2">
                <span>⭐</span> COMUNIDAD OFICIAL
            </div>
            <h2 class="text-lg sm:text-xl font-black text-white leading-tight">
                Invierte y gana del <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 font-mono">5% al 7% diario</span>
            </h2>
            <p class="text-[11px] text-slate-300 mt-1 max-w-[280px]">
                Retiros automáticos 24/7 a Nequi, Daviplata y Bancolombia.
            </p>
        </div>

        <div class="relative z-10 flex items-center justify-between pt-3 border-t border-slate-800/80 mt-2">
            <div class="flex items-center gap-2">
                <span class="text-[10px] text-slate-400">Saldo Disponible:</span>
                <span class="text-base font-black text-emerald-400 font-mono user-balance-display transition-all duration-300">${{ number_format(Auth::user()->balance, 0, ',', '.') }} COP</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('cliente.deposits.index') }}" class="px-3 py-1 bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black rounded-xl text-xs shadow-md transition active:scale-95">
                    ➕ Recargar
                </a>
                <a href="{{ route('cliente.withdrawals.index') }}" class="px-3 py-1 bg-slate-900 border border-slate-700 hover:border-cyan-500/40 text-cyan-300 font-bold rounded-xl text-xs transition active:scale-95">
                    💸 Retirar
                </a>
            </div>
        </div>
    </div>

    <!-- 3. BARRA DE AVISOS CON ALTAVOZ (📢 SPEAKER MARQUEE NOTICES) -->
    <div class="flex items-center gap-2.5 bg-slate-900/90 border border-slate-800 rounded-2xl px-3.5 py-2.5 shadow-md overflow-hidden">
        <div class="w-6 h-6 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-xs flex-shrink-0">
            📢
        </div>
        <div class="overflow-hidden whitespace-nowrap text-xs text-slate-300 flex-1">
            <div class="animate-marquee inline-block">
                <span>¡Bienvenidos a la plataforma oficial FORTEX! • Servidores de procesamiento y rendimientos en COP • Retiros a Nequi y Daviplata en menos de 15 minutos • Gana 10% directo por cada amigo invitado •</span>
            </div>
        </div>
    </div>

    <!-- 4. REJILLA DE 6 ICONOS DE ACCIÓN PRINCIPALES (3x2 PERFECTAMENTE ALINEADOS) -->
    <div class="grid grid-cols-3 gap-2.5 bg-slate-900/70 border border-slate-800/90 rounded-3xl p-3.5 shadow-xl text-center">
        <!-- 1. Retiro -->
        <a href="{{ route('cliente.withdrawals.index') }}" class="group flex flex-col items-center justify-center p-2 rounded-2xl hover:bg-slate-800/60 transition active:scale-95">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-cyan-500/20 to-blue-500/20 border border-cyan-500/40 text-cyan-300 flex items-center justify-center text-xl mb-1 shadow-md group-hover:scale-110 transition">
                💸
            </div>
            <span class="text-xs font-bold text-slate-200">Retiro</span>
            <span class="text-[9px] text-slate-500 font-semibold">Mín $15.000</span>
        </a>

        <!-- 2. Recarga -->
        <a href="{{ route('cliente.deposits.index') }}" class="group flex flex-col items-center justify-center p-2 rounded-2xl hover:bg-slate-800/60 transition active:scale-95">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500/20 to-teal-500/20 border border-emerald-500/40 text-emerald-300 flex items-center justify-center text-xl mb-1 shadow-md group-hover:scale-110 transition">
                💳
            </div>
            <span class="text-xs font-bold text-slate-200">Recarga</span>
            <span class="text-[9px] text-slate-500 font-semibold">Nequi / QR</span>
        </a>

        <!-- 3. Finanzas -->
        <a href="{{ route('cliente.plans.index') }}" class="group flex flex-col items-center justify-center p-2 rounded-2xl hover:bg-slate-800/60 transition active:scale-95">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-500/20 to-indigo-500/20 border border-purple-500/40 text-purple-300 flex items-center justify-center text-xl mb-1 shadow-md group-hover:scale-110 transition">
                📈
            </div>
            <span class="text-xs font-bold text-slate-200">Finanzas</span>
            <span class="text-[9px] text-slate-500 font-semibold">Rendimientos</span>
        </a>

        <!-- 4. Centro de Ayuda -->
        <button type="button" onclick="openSupportModal()" class="group flex flex-col items-center justify-center p-2 rounded-2xl hover:bg-slate-800/60 transition active:scale-95 cursor-pointer">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-sky-500/20 to-cyan-500/20 border border-sky-500/40 text-sky-300 flex items-center justify-center text-xl mb-1 shadow-md group-hover:scale-110 transition">
                👥
            </div>
            <span class="text-xs font-bold text-slate-200">Ayuda</span>
            <span class="text-[9px] text-slate-500 font-semibold">Soporte 24/7</span>
        </button>

        <!-- 5. Invitar -->
        <a href="{{ route('cliente.team.index') }}" class="group flex flex-col items-center justify-center p-2 rounded-2xl hover:bg-slate-800/60 transition active:scale-95">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500/20 to-yellow-500/20 border border-amber-500/40 text-amber-300 flex items-center justify-center text-xl mb-1 shadow-md group-hover:scale-110 transition">
                🔗
            </div>
            <span class="text-xs font-bold text-slate-200">Invitar</span>
            <span class="text-[9px] text-slate-500 font-semibold">10% Directo</span>
        </a>

        <!-- 6. Sobre Nosotros -->
        <button type="button" onclick="openAboutModal()" class="group flex flex-col items-center justify-center p-2 rounded-2xl hover:bg-slate-800/60 transition active:scale-95 cursor-pointer">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-teal-500/20 to-emerald-500/20 border border-teal-500/40 text-teal-300 flex items-center justify-center text-xl mb-1 shadow-md group-hover:scale-110 transition">
                ℹ️
            </div>
            <span class="text-xs font-bold text-slate-200">Nosotros</span>
            <span class="text-[9px] text-slate-500 font-semibold">Seguridad</span>
        </button>
    </div>

    <!-- 5. BANNER HORIZONTAL: CERTIFICACIÓN Y SEGURIDAD OFICIAL -->
    <div onclick="openAboutModal()" class="bg-gradient-to-r from-emerald-950/90 via-slate-900 to-teal-950 border border-emerald-500/40 hover:border-emerald-400/80 rounded-2xl p-3.5 flex items-center justify-between shadow-xl shadow-emerald-950/40 cursor-pointer transition active:scale-[0.98]">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center text-xl flex-shrink-0 shadow-inner">
                🛡️
            </div>
            <div>
                <div class="flex items-center gap-1.5">
                    <h3 class="text-xs sm:text-sm font-extrabold text-white">Certificado y Licencia de Operación</h3>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
                <p class="text-[10px] text-slate-300">Auditoría 2026 • Fondos 100% respaldados y cifrado 256-bit</p>
            </div>
        </div>
        <div class="flex items-center gap-1 text-emerald-400 text-xs font-black bg-emerald-500/15 border border-emerald-500/30 px-3 py-1.5 rounded-xl">
            <span>Verificado</span>
            <span class="text-sm">✓</span>
        </div>
    </div>

    <!-- 6. TARJETAS DE INTERACCIÓN: RULETA DE LA SUERTE & SOBRE ROJO -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Ruleta de la Suerte -->
        <div onclick="openRouletteModal()" class="bg-gradient-to-br from-amber-950/80 via-slate-900 to-slate-900 border border-amber-500/40 rounded-2xl p-3.5 cursor-pointer hover:border-amber-400 transition shadow-lg flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">🎡</span>
                <span class="text-[9px] px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400 font-extrabold">DIARIO</span>
            </div>
            <div>
                <h4 class="text-xs font-bold text-white">Ruleta de la suerte &gt;</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Gana bonos en efectivo</p>
            </div>
        </div>

        <!-- Sobre Rojo / Bono -->
        <div onclick="openRedPacketModal()" class="bg-gradient-to-br from-rose-950/80 via-slate-900 to-slate-900 border border-rose-500/40 rounded-2xl p-3.5 cursor-pointer hover:border-rose-400 transition shadow-lg flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">🧧</span>
                <span class="text-[9px] px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-400 font-extrabold">REGALO</span>
            </div>
            <div>
                <h4 class="text-xs font-bold text-white">Sobre rojo VIP &gt;</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Bono de bienvenida</p>
            </div>
        </div>
    </div>

    <!-- 7. TUS PAQUETES ACTIVOS (CON CONTADOR DE 24 HORAS EN VIVO) -->
    <div id="mis-planes" class="space-y-3 pt-2">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs sm:text-sm font-extrabold text-white flex items-center gap-1.5">
                <span>⚡</span> Tus Paquetes Activos
            </h3>
            <span class="text-[11px] text-emerald-400 font-bold font-mono">{{ $userPlans->count() }} Activos</span>
        </div>

        @forelse($userPlans as $up)
            <div class="bg-slate-900/90 border border-slate-800 hover:border-emerald-500/40 rounded-3xl p-4 shadow-xl space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-extrabold uppercase border border-emerald-500/30">
                            {{ $up->plan->name }}
                        </span>
                        <span class="text-xs text-white font-mono font-bold">${{ number_format($up->invested_amount, 0, ',', '.') }} COP</span>
                    </div>
                    <span class="text-xs font-mono font-bold text-emerald-400">+{{ $up->plan->daily_percentage }}% diario</span>
                </div>

                <!-- Progreso -->
                <div>
                    <div class="flex justify-between text-[10px] text-slate-400 mb-1">
                        <span>Ganado: <strong class="text-emerald-400 font-mono" id="plan-earned-{{ $up->id }}">${{ number_format($up->earned_so_far, 0, ',', '.') }}</strong></span>
                        <span>Tope: <strong class="text-amber-400 font-mono">${{ number_format($up->max_earning, 0, ',', '.') }} COP</strong></span>
                    </div>
                    @php
                        $percent = $up->max_earning > 0 ? min(100, round(($up->earned_so_far / $up->max_earning) * 100)) : 0;
                    @endphp
                    <div class="w-full bg-slate-950 rounded-full h-2 overflow-hidden border border-slate-800">
                        <div id="plan-progress-{{ $up->id }}" class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                </div>

                <!-- Botón o Cuenta Regresiva de 24 Horas -->
                <div id="plan-action-container-{{ $up->id }}">
                    @if(!$up->canClaim())
                        <div class="py-2.5 px-3.5 bg-slate-950 border border-slate-800 rounded-xl flex items-center justify-between text-xs">
                            <span class="text-slate-400">⏳ Próximo reclamo:</span>
                            <span class="countdown-timer font-mono text-amber-400 font-extrabold" data-seconds="{{ $up->secondsUntilNextClaim() }}">Calculando...</span>
                        </div>
                    @else
                        <form method="POST" action="{{ route('cliente.plans.claim', $up->id) }}" onsubmit="handleClaimDaily(event, {{ $up->id }}, '{{ route('cliente.plans.claim', $up->id) }}')">
                            @csrf
                            <button type="submit" id="btn-claim-{{ $up->id }}" class="w-full py-3 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-600 hover:to-teal-600 text-slate-950 font-black rounded-xl text-xs shadow-lg shadow-emerald-500/25 transition active:scale-95 cursor-pointer animate-pulse">
                                🎁 Reclamar Ganancia (+${{ number_format($up->daily_earning, 0, ',', '.') }} COP)
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-slate-900/40 border border-slate-800 rounded-2xl p-6 text-center text-slate-400 text-xs">
                <span class="text-2xl block mb-1">📦</span>
                No tienes paquetes activos. Elige uno abajo para empezar a generar rendimientos diarios.
            </div>
        @endforelse
    </div>

    <!-- 8. CATÁLOGO DE PLANES VIP (COMPRA CON SALDO DISPONIBLE) -->
    <div class="space-y-3 pt-2">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs sm:text-sm font-extrabold text-white flex items-center gap-1.5">
                <span>⭐</span> Membresías VIP Disponibles
            </h3>
            <span class="text-[10px] text-slate-400">Valores en $ COP</span>
        </div>

        <div class="space-y-3">
            @foreach($availablePlans as $plan)
                <div class="bg-slate-900/90 border border-slate-800 hover:border-emerald-500/40 rounded-3xl p-4 shadow-xl flex flex-col justify-between transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-200 text-[10px] font-extrabold uppercase border border-slate-700">
                            {{ $plan->badge ?? 'VIP' }}
                        </span>
                        <span class="text-xs font-bold text-emerald-400 font-mono">{{ $plan->daily_percentage }}% diario</span>
                    </div>

                    <div class="flex items-center justify-between my-1">
                        <div>
                            <h4 class="text-sm font-black text-white">{{ $plan->name }}</h4>
                            <p class="text-[10px] text-slate-400">Paga ${{ number_format(($plan->price * $plan->daily_percentage) / 100, 0, ',', '.') }} COP / día ({{ $plan->duration_days }} días)</p>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-black text-white font-mono">${{ number_format($plan->price, 0, ',', '.') }}</span>
                            <span class="text-[10px] text-slate-500 block">COP</span>
                        </div>
                    </div>

                    <form id="buy-plan-app-{{ $plan->id }}" method="POST" action="{{ route('cliente.plans.buy', $plan->id) }}" class="mt-2">
                        @csrf
                        <button type="button" onclick="Swal.fire({
                            title: '¿Activar {{ $plan->name }}?',
                            html: 'Se descontarán <b class=\'text-emerald-400\'>${{ number_format($plan->price, 0, ',', '.') }} COP</b> de tu saldo disponible.<br><br><div class=\'bg-slate-950 p-3 rounded-xl border border-slate-800 text-left text-xs space-y-1 text-slate-300\'><div>⚡ Rendimiento: <b class=\'text-emerald-400\'>{{ $plan->daily_percentage }}% diario</b></div><div>📅 Duración: <b>{{ $plan->duration_days }} días</b></div><div>💰 Retorno Total: <b class=\'text-amber-400\'>${{ number_format($plan->max_return, 0, ',', '.') }} COP</b></div></div>',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#334155',
                            confirmButtonText: '⚡ Sí, Activar Ahora',
                            cancelButtonText: 'Cancelar',
                            customClass: { popup: 'swal-custom-dark' }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('buy-plan-app-{{ $plan->id }}').submit();
                            }
                        })" class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-slate-950 font-black rounded-xl text-xs shadow-md transition active:scale-95 cursor-pointer">
                            ⚡ Activar {{ $plan->name }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- MODALES INTERACTIVOS (SOPORTE, RULETA, SOBRE ROJO, LEGALIDAD) -->
<!-- ========================================== -->

<!-- Modal Soporte / Centro de Ayuda -->
<div id="supportModal" class="fixed inset-0 bg-black/85 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 max-w-sm w-full shadow-2xl relative text-center">
        <button onclick="closeSupportModal()" class="absolute right-4 top-4 text-slate-400 hover:text-white text-xl font-bold">✕</button>
        <span class="text-4xl block mb-2">🎧</span>
        <h3 class="text-base font-extrabold text-white">Centro de Ayuda VIP</h3>
        <p class="text-xs text-slate-400 mt-1 mb-4">¿Tienes dudas con tus recargas, retiros o ganancias? Contáctanos de inmediato.</p>
        
        <div class="space-y-2 text-xs">
            <a href="https://t.me/" target="_blank" class="w-full py-3 bg-cyan-500/20 hover:bg-cyan-500/30 border border-cyan-500/40 text-cyan-300 font-bold rounded-xl flex items-center justify-center gap-2 transition">
                <span>✈️</span> Canal Oficial en Telegram
            </a>
            <a href="https://api.whatsapp.com/send?phone=" target="_blank" class="w-full py-3 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/40 text-emerald-300 font-bold rounded-xl flex items-center justify-center gap-2 transition">
                <span>💬</span> Asesor Oficial WhatsApp
            </a>
        </div>
    </div>
</div>

<!-- Modal Sobre Nosotros, Certificación y Seguridad Oficial -->
<div id="aboutModal" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 hover:border-emerald-500/40 rounded-3xl p-5 sm:p-7 max-w-md w-full shadow-2xl relative max-h-[92vh] overflow-y-auto">
        <button onclick="closeAboutModal()" class="absolute right-4 top-4 text-slate-400 hover:text-white text-xl font-bold transition cursor-pointer">✕</button>
        
        <!-- Insignia Superior de Verificación -->
        <div class="flex flex-col items-center text-center mb-4">
            <div class="relative mb-2">
                <div class="w-16 h-16 rounded-2xl bg-black border-2 border-emerald-500/50 p-1 flex items-center justify-center shadow-xl shadow-emerald-500/20">
                    <img src="{{ asset('img/fortex.jpg') }}" alt="FORTEX Logo" class="w-full h-full object-cover rounded-xl">
                </div>
                <span class="absolute -bottom-1 -right-1 bg-emerald-500 text-slate-950 text-[11px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-slate-900 shadow">
                    ✓
                </span>
            </div>
            
            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-[10px] font-black tracking-widest uppercase mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> INFRAESTRUCTURA CLOUD VERIFICADA
            </span>
            <h3 class="text-base sm:text-lg font-black text-white">FORTEX</h3>
            <p class="text-[11px] text-slate-400">Plataforma Oficial de Inversión y Cómputo Cloud</p>
        </div>

        <!-- Recuadro del Certificado Oficial -->
        <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 space-y-3 mb-4 text-xs">
            <!-- Datos de Red y Servidores -->
            <div class="grid grid-cols-2 gap-2 pb-3 border-b border-slate-800/80 text-[11px]">
                <div>
                    <span class="text-slate-500 text-[10px] block font-semibold">Licencia Operativa:</span>
                    <span class="font-mono font-bold text-emerald-400">FTX-2026-CLOUD</span>
                </div>
                <div>
                    <span class="text-slate-500 text-[10px] block font-semibold">Seguridad de Red:</span>
                    <span class="font-mono font-bold text-white">Tier IV Enterprise</span>
                </div>
                <div>
                    <span class="text-slate-500 text-[10px] block font-semibold">Disponibilidad SLA:</span>
                    <span class="font-bold text-slate-300">99.98% Activo</span>
                </div>
                <div>
                    <span class="text-slate-500 text-[10px] block font-semibold">Protocolo de Cifrado:</span>
                    <span class="font-bold text-amber-400">TLS 1.3 / AES-256</span>
                </div>
            </div>

            <!-- Garantías y Protocolos Clave -->
            <div class="space-y-2.5 text-[11px] text-slate-300">
                <div class="flex items-start gap-2.5">
                    <span class="text-base leading-none">🛡️</span>
                    <div>
                        <strong class="text-white">Garantía de Retiros Automatizados:</strong>
                        <p class="text-slate-400 text-[10px] leading-relaxed mt-0.5">Pagos directos en Colombia a cuentas Bancolombia, Nequi y Daviplata con liquidación prioritaria en menos de 15 minutos.</p>
                    </div>
                </div>

                <div class="flex items-start gap-2.5">
                    <span class="text-base leading-none">🖥️</span>
                    <div>
                        <strong class="text-white">Infraestructura de Servidores FORTEX:</strong>
                        <p class="text-slate-400 text-[10px] leading-relaxed mt-0.5">Tu inversión participa en la capacidad operativa de centros de datos y computación gráfica de alto rendimiento con 99.98% de disponibilidad.</p>
                    </div>
                </div>

                <div class="flex items-start gap-2.5">
                    <span class="text-base leading-none">🔐</span>
                    <div>
                        <strong class="text-white">Seguridad y Cifrado Bancario:</strong>
                        <p class="text-slate-400 text-[10px] leading-relaxed mt-0.5">Conexión cifrada bajo el estándar internacional TLS 1.3 con clave criptográfica AES de 256 bits, garantizando la confidencialidad de tu saldo y transferencias.</p>
                    </div>
                </div>
            </div>

            <!-- Sello Digital de Autenticidad -->
            <div class="pt-2 border-t border-slate-800/80 flex items-center justify-between text-[10px] text-slate-500 font-mono">
                <span>Licencia de Red: #FTX-8840-CO</span>
                <span class="text-emerald-400 font-bold flex items-center gap-1">
                    <span>🔒</span> Servidor Verificado y En Línea
                </span>
            </div>
        </div>

        <!-- Botón de Confirmación Seguro -->
        <button onclick="closeAboutModal()" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-slate-950 font-black rounded-xl text-xs sm:text-sm transition active:scale-95 shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 cursor-pointer">
            <span>🛡️</span> Confirmar y Continuar Seguro
        </button>
    </div>
</div>

<!-- Modal Ruleta de la Suerte VIP -->
<div id="rouletteModal" class="fixed inset-0 bg-black/85 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-amber-500/30 rounded-3xl p-6 max-w-sm w-full shadow-2xl relative text-center">
        <button onclick="closeRouletteModal()" class="absolute right-4 top-4 text-slate-400 hover:text-white text-xl font-bold cursor-pointer">✕</button>
        
        <div class="flex items-center justify-center gap-2 mb-1">
            <span class="text-2xl">🎡</span>
            <h3 class="text-base font-black text-white">Ruleta de la Suerte VIP</h3>
        </div>
        
        <!-- Contador de Giros Disponibles -->
        <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 text-xs font-black mb-3">
            <span>🎟️</span> Giros Disponibles: <span id="spinsLeftBadge" class="text-white font-mono text-sm">{{ Auth::user()->roulette_spins ?? 1 }}</span>
        </div>

        <!-- RUEDA GIRATORIA VISUAL CON PREMIOS -->
        <div class="relative w-64 h-64 mx-auto mb-3 flex items-center justify-center">
            <!-- Puntero Superior Dorado -->
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 z-20 w-0 h-0 border-l-[10px] border-l-transparent border-r-[10px] border-r-transparent border-t-[20px] border-t-amber-400 filter drop-shadow-[0_4px_8px_rgba(245,158,11,0.8)]"></div>

            <!-- Disco Circular de la Ruleta -->
            <div id="rouletteWheel" class="w-full h-full rounded-full border-4 border-amber-400/80 shadow-[0_0_30px_rgba(245,158,11,0.3)] relative overflow-hidden transition-all duration-[4000ms] ease-out flex items-center justify-center select-none" style="background: conic-gradient(#10b981 0deg 45deg, #06b6d4 45deg 90deg, #f59e0b 90deg 135deg, #ec4899 135deg 180deg, #8b5cf6 180deg 225deg, #ef4444 225deg 270deg, #14b8a6 270deg 315deg, #3b82f6 315deg 360deg);">
                
                <!-- Etiquetas de Premios Centradas Radialmente (0 a 7) -->
                <div class="absolute inset-0 text-[10px] font-black text-white pointer-events-none">
                    <!-- Slice 0 (0°-45°) -->
                    <div class="absolute top-0 left-1/2 w-16 -ml-8 h-1/2 pt-2 text-center origin-bottom font-mono font-bold" style="transform: rotate(22.5deg);">$1.000</div>
                    <!-- Slice 1 (45°-90°) -->
                    <div class="absolute top-0 left-1/2 w-16 -ml-8 h-1/2 pt-2 text-center origin-bottom font-mono font-bold" style="transform: rotate(67.5deg);">$2.000</div>
                    <!-- Slice 2 (90°-135°) -->
                    <div class="absolute top-0 left-1/2 w-16 -ml-8 h-1/2 pt-2 text-center origin-bottom font-mono font-bold" style="transform: rotate(112.5deg);">$5.000</div>
                    <!-- Slice 3 (135°-180°) -->
                    <div class="absolute top-0 left-1/2 w-16 -ml-8 h-1/2 pt-2 text-center origin-bottom font-mono font-bold" style="transform: rotate(157.5deg);">$9.000</div>
                    <!-- Slice 4 (180°-225°) -->
                    <div class="absolute top-0 left-1/2 w-16 -ml-8 h-1/2 pt-2 text-center origin-bottom font-mono font-bold" style="transform: rotate(202.5deg);">$500</div>
                    <!-- Slice 5 (225°-270°) Premio Mayor -->
                    <div class="absolute top-0 left-1/2 w-20 -ml-10 h-1/2 pt-1.5 text-center origin-bottom text-yellow-300 font-mono font-black text-[9px] leading-tight" style="transform: rotate(247.5deg);">👑 $13.000</div>
                    <!-- Slice 6 (270°-315°) -->
                    <div class="absolute top-0 left-1/2 w-16 -ml-8 h-1/2 pt-2 text-center origin-bottom font-mono font-bold" style="transform: rotate(292.5deg);">$3.000</div>
                    <!-- Slice 7 (315°-360°) -->
                    <div class="absolute top-0 left-1/2 w-16 -ml-8 h-1/2 pt-2 text-center origin-bottom font-mono font-bold" style="transform: rotate(337.5deg);">$1.000</div>
                </div>

                <!-- Botón Central de Giro -->
                <button id="spinBtn" type="button" onclick="spinRoulette()" class="absolute z-10 w-16 h-16 rounded-full bg-slate-950 border-4 border-amber-400 text-amber-400 font-black text-xs flex flex-col items-center justify-center shadow-2xl hover:scale-105 active:scale-95 transition cursor-pointer">
                    <span>GIRAR</span>
                    <span class="text-[8px] text-slate-400 font-mono" id="spinCenterLabel">{{ (Auth::user()->roulette_spins ?? 1) > 0 ? (Auth::user()->roulette_spins ?? 1) . 'x' : '0x' }}</span>
                </button>
            </div>
        </div>

        <div id="rouletteStatusMessage" class="text-[11px] text-amber-400/90 font-medium mb-3">
            {{ (Auth::user()->roulette_spins ?? 1) > 0 ? '¡Presiona GIRAR para probar tu suerte!' : '¡Invita amigos para ganar más giros!' }}
        </div>

        <!-- Banner Explicativo de Dinámica de Recarga y Giros -->
        <div class="bg-slate-950 p-3 rounded-2xl border border-slate-800 text-left text-[11px] space-y-1.5">
            <div class="flex items-center justify-between text-amber-300 font-bold">
                <span class="flex items-center gap-1.5"><span>⚡</span> Recompensas de la Ruleta</span>
                <span class="text-[9px] px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 font-mono">👑 Hasta $13.000 COP</span>
            </div>
            <p class="text-slate-400 text-[10px] leading-tight">
                💳 <strong>Por cada recarga recibes +3 Giros Gratis.</strong> Tus primeros 2 giros aseguran $1.000 COP cada uno, y el 3er giro en adelante participa aleatoriamente por todos los premios de hasta <strong class="text-emerald-400">$13.000 COP</strong>!
            </p>
            <div class="grid grid-cols-2 gap-2 pt-1">
                <a href="{{ route('cliente.deposits.index') }}" class="py-2 bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black text-center rounded-xl text-[11px] shadow-md transition active:scale-95">
                    ➕ Recargar (+3 Giros)
                </a>
                <a href="{{ route('cliente.team.index') }}" class="py-2 bg-slate-800 border border-slate-700 hover:border-amber-500/40 text-amber-300 font-bold text-center rounded-xl text-[11px] transition active:scale-95">
                    🔗 Invitar (+2 Giros)
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sobre Rojo VIP & Canje de Código -->
<div id="redPacketModal" class="fixed inset-0 bg-black/85 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-rose-500/30 rounded-3xl p-6 max-w-sm w-full shadow-2xl relative text-center">
        <button onclick="closeRedPacketModal()" class="absolute right-4 top-4 text-slate-400 hover:text-white text-xl font-bold cursor-pointer">✕</button>
        
        <div class="flex items-center justify-center gap-2 mb-1">
            <span class="text-2xl animate-pulse">🧧</span>
            <h3 class="text-base font-black text-white">Sobre Rojo de Recompensas</h3>
        </div>
        <p class="text-[11px] text-slate-400 mb-4">Abre tu bono de bienvenida sorpresa o canjea un código exclusivo.</p>

        <!-- SOBRE ROJO ANIMADO 3D -->
        <div class="bg-gradient-to-br from-rose-950 via-red-900 to-rose-950 border-2 border-rose-500/50 rounded-3xl p-5 mb-4 shadow-xl relative overflow-hidden group">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-400/20 border border-amber-400/40 text-amber-400 flex items-center justify-center text-3xl mb-2 shadow-inner animate-bounce">
                🧧
            </div>
            <h4 class="text-sm font-black text-white">Bono de Bienvenida VIP</h4>
            <p class="text-[10px] text-rose-200 mt-0.5">¡Reclama tu regalo especial para nuevos miembros!</p>

            <button id="openWelcomePacketBtn" onclick="claimWelcomePacket()" class="mt-3 w-full py-2.5 bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-slate-950 font-black rounded-xl text-xs shadow-lg transition active:scale-95 cursor-pointer">
                🎁 ¡Abrir Mi Sobre Rojo!
            </button>
        </div>

        <!-- SECCIÓN 2: CANJEAR CÓDIGO PROMOCIONAL DE TELEGRAM -->
        <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 text-left">
            <label class="block text-[11px] font-bold text-slate-300 mb-1.5 flex items-center justify-between">
                <span>¿Tienes un Código Secreto?</span>
                <span class="text-[9px] text-emerald-400">Telegram / Redes</span>
            </label>
            
            <div class="flex gap-2">
                <input type="text" id="promoCodeInput" placeholder="Ej: VIP2026" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white font-mono uppercase text-xs focus:outline-none focus:border-rose-500">
                <button onclick="claimPromoCode()" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs whitespace-nowrap transition cursor-pointer active:scale-95">
                    Canjear
                </button>
            </div>
            <span class="text-[9px] text-slate-500 mt-1 block">Prueba con los códigos: <b class="text-slate-400">VIP2026</b>, <b class="text-slate-400">BONO777</b> o <b class="text-slate-400">FORTEX</b></span>
        </div>
    </div>
</div>

<script>
    function openSupportModal() { document.getElementById('supportModal').classList.remove('hidden'); }
    function closeSupportModal() { document.getElementById('supportModal').classList.add('hidden'); }

    function openAboutModal() { document.getElementById('aboutModal').classList.remove('hidden'); }
    function closeAboutModal() { document.getElementById('aboutModal').classList.add('hidden'); }

    function openRouletteModal() { document.getElementById('rouletteModal').classList.remove('hidden'); }
    function closeRouletteModal() { document.getElementById('rouletteModal').classList.add('hidden'); }

    function openRedPacketModal() { document.getElementById('redPacketModal').classList.remove('hidden'); }
    function closeRedPacketModal() { document.getElementById('redPacketModal').classList.add('hidden'); }

    // ==========================================
    // LÓGICA DE GIRO DE LA RULETA DE LA SUERTE
    // ==========================================
    let isSpinning = false;
    let currentRotation = 0;

    async function spinRoulette() {
        if (isSpinning) return;

        const spinBtn = document.getElementById('spinBtn');
        const wheel = document.getElementById('rouletteWheel');
        const statusMsg = document.getElementById('rouletteStatusMessage');

        isSpinning = true;
        spinBtn.disabled = true;
        spinBtn.classList.add('opacity-50', 'cursor-not-allowed');
        statusMsg.innerText = '⚡ Girando la ruleta de la suerte...';

        try {
            const response = await fetch("{{ route('cliente.rewards.spin') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                isSpinning = false;
                spinBtn.disabled = false;
                spinBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                statusMsg.innerText = data.message || 'Intenta más tarde.';
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Ruleta Diaria',
                    text: data.message,
                    customClass: { popup: 'swal-custom-dark' },
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            // Cada segmento mide 45 grados (360 / 8)
            const segmentDegrees = 45;
            const targetSegment = data.segment_index;
            
            // Calculamos 5 vueltas completas + el ángulo exacto hacia el puntero superior
            const extraRounds = 360 * 5;
            const targetAngle = 360 - (targetSegment * segmentDegrees) - (segmentDegrees / 2);
            currentRotation += extraRounds + targetAngle;

            wheel.style.transform = `rotate(${currentRotation}deg)`;

            setTimeout(() => {
                isSpinning = false;
                spinBtn.disabled = false;
                spinBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                statusMsg.innerText = `🎉 ¡Ganaste ${data.prize_label}!`;

                Swal.fire({
                    icon: 'success',
                    title: '¡Felicidades!',
                    html: `Has ganado <b class="text-amber-400 text-lg font-mono">+${data.prize_label}</b><br><br>El dinero ya fue acreditado a tu balance disponible.`,
                    customClass: { popup: 'swal-custom-dark' },
                    confirmButtonColor: '#10b981',
                    confirmButtonText: '¡Excelente!'
                }).then(() => {
                    window.location.reload();
                });
            }, 4100);

        } catch (err) {
            isSpinning = false;
            spinBtn.disabled = false;
            spinBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            statusMsg.innerText = 'Ocurrió un error. Intenta nuevamente.';
        }
    }

    // ==========================================
    // LÓGICA DEL SOBRE ROJO Y CANJE DE CÓDIGOS
    // ==========================================
    async function claimWelcomePacket() {
        try {
            const response = await fetch("{{ route('cliente.rewards.red-packet') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sobre Rojo',
                    text: data.message,
                    customClass: { popup: 'swal-custom-dark' },
                    confirmButtonColor: '#ef4444'
                });
                return;
            }

            closeRedPacketModal();

            Swal.fire({
                icon: 'success',
                title: '¡Sobre Rojo Abierto!',
                html: data.message,
                customClass: { popup: 'swal-custom-dark' },
                confirmButtonColor: '#10b981',
                confirmButtonText: '¡Genial!'
            }).then(() => {
                window.location.reload();
            });

        } catch (err) {
            alert('Ocurrió un error al procesar el sobre rojo.');
        }
    }

    async function claimPromoCode() {
        const input = document.getElementById('promoCodeInput');
        const code = input.value.trim();

        if (!code) {
            Swal.fire({
                icon: 'warning',
                title: 'Código Requerido',
                text: 'Por favor escribe un código promocional.',
                customClass: { popup: 'swal-custom-dark' },
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        try {
            const response = await fetch("{{ route('cliente.rewards.red-packet') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Código No Válido',
                    text: data.message,
                    customClass: { popup: 'swal-custom-dark' },
                    confirmButtonColor: '#ef4444'
                });
                return;
            }

            closeRedPacketModal();
            input.value = '';

            Swal.fire({
                icon: 'success',
                title: '¡Código Canjeado!',
                html: data.message,
                customClass: { popup: 'swal-custom-dark' },
                confirmButtonColor: '#10b981',
                confirmButtonText: '¡Aceptar!'
            }).then(() => {
                window.location.reload();
            });

        } catch (err) {
            alert('Error al canjear el código.');
        }
    }

    // Reclamar Ganancia Diaria instantáneamente sin alertas molestas
    async function handleClaimDaily(event, planId, url) {
        event.preventDefault();
        const btn = document.getElementById(`btn-claim-${planId}`);
        if (!btn || btn.disabled) return;

        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span>⏳ Acreditando...</span>';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
                btn.innerHTML = originalText;
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: data.message || 'No se pudo reclamar en este momento.',
                    customClass: { popup: 'swal-custom-dark' },
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            // 1. Actualizar balances en pantalla instantáneamente
            document.querySelectorAll('.user-balance-value').forEach(el => {
                el.innerText = `$${data.new_balance_formatted}`;
            });
            document.querySelectorAll('.user-balance-display').forEach(el => {
                el.innerText = `$${data.new_balance_formatted} COP`;
            });

            // Pequeño realce visual al actualizar saldo
            document.querySelectorAll('.user-balance-display, .user-balance-value').forEach(el => {
                el.classList.add('scale-105', 'text-emerald-300');
                setTimeout(() => el.classList.remove('scale-105', 'text-emerald-300'), 500);
            });

            // 2. Actualizar acumulado ganado y barra de progreso
            const earnedEl = document.getElementById(`plan-earned-${planId}`);
            if (earnedEl) {
                earnedEl.innerText = `$${data.earned_so_far_formatted}`;
            }
            const progressEl = document.getElementById(`plan-progress-${planId}`);
            if (progressEl) {
                progressEl.style.width = `${data.percent}%`;
            }

            // 3. Reemplazar botón por el temporizador de 24 horas sin ninguna ventana de alerta
            const container = document.getElementById(`plan-action-container-${planId}`);
            if (container) {
                if (data.status === 'completed') {
                    container.innerHTML = `
                        <div class="py-2.5 px-3.5 bg-slate-950 border border-emerald-500/30 rounded-xl flex items-center justify-between text-xs">
                            <span class="text-emerald-400 font-bold">✅ Paquete Completado</span>
                            <span class="font-mono text-emerald-400 text-[10px]">100% Retorno</span>
                        </div>
                    `;
                } else {
                    container.innerHTML = `
                        <div class="py-2.5 px-3.5 bg-slate-950 border border-slate-800 rounded-xl flex items-center justify-between text-xs">
                            <span class="text-slate-400">⏳ Próximo reclamo:</span>
                            <span class="countdown-timer font-mono text-amber-400 font-extrabold" data-seconds="${data.next_seconds}">Calculando...</span>
                        </div>
                    `;
                    startCountdownTimers();
                }
            }

        } catch (err) {
            console.error('Error al reclamar ganancia:', err);
            const form = btn.closest('form');
            if (form) form.submit();
        }
    }

    // Cuenta regresiva de 24 horas para reclamos de paquetes
    function startCountdownTimers() {
        const timers = document.querySelectorAll('.countdown-timer');
        timers.forEach(timer => {
            if (timer.dataset.timerRunning === 'true') return;
            timer.dataset.timerRunning = 'true';

            let seconds = parseInt(timer.getAttribute('data-seconds'), 10);
            if (isNaN(seconds) || seconds <= 0) {
                timer.innerText = "¡Listo para reclamar!";
                return;
            }

            const updateTimer = () => {
                if (seconds <= 0) {
                    timer.innerText = "¡Listo para reclamar!";
                    setTimeout(() => window.location.reload(), 1500);
                    return;
                }
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = seconds % 60;
                timer.innerText = `${h.toString().padStart(2, '0')}h ${m.toString().padStart(2, '0')}m ${s.toString().padStart(2, '0')}s`;
                seconds--;
                setTimeout(updateTimer, 1000);
            };
            updateTimer();
        });
    }
    document.addEventListener('DOMContentLoaded', startCountdownTimers);
</script>
@endsection
