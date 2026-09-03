@extends('layouts.cliente')

@section('title', 'Membresías y Rendimientos')

@section('content')
<div class="space-y-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white flex items-center gap-2">
                <span>⚡</span> Membresías y Rendimiento Diario
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">Activa paquetes y reclama tu porcentaje cada 24 horas.</p>
        </div>
        <div class="text-right">
            <span class="text-[11px] text-slate-400">Saldo:</span>
            <p class="text-base font-extrabold text-emerald-400 font-mono">${{ number_format($user->balance, 0, ',', '.') }} COP</p>
        </div>
    </div>

    <!-- 1. Tus Planes Activos -->
    <div>
        <h2 class="text-sm font-extrabold text-white mb-3 flex items-center gap-2 px-1">
            <span>🔥</span> Tus Planes en Curso ({{ $activePlans->count() }})
        </h2>

        @if($activePlans->isEmpty())
            <div class="bg-slate-900/40 border border-slate-800 rounded-3xl p-6 text-center">
                <span class="text-3xl block mb-2">📦</span>
                <h3 class="text-sm font-bold text-white">No tienes planes activos</h3>
                <p class="text-xs text-slate-400 mt-1">Elige uno de los paquetes VIP disponibles abajo para empezar a generar ganancias.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($activePlans as $up)
                    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-5 relative overflow-hidden shadow-xl">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-extrabold uppercase">Activo</span>
                                <h4 class="text-lg font-extrabold text-white mt-1">{{ $up->plan->name }}</h4>
                            </div>
                            <div class="text-right">
                                <span class="text-[11px] text-slate-400">Rendimiento</span>
                                <p class="text-sm font-extrabold text-emerald-400 font-mono">+${{ number_format($up->daily_earning, 0, ',', '.') }} COP / día</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-slate-400 mb-1">
                                <span>Progreso del Tope:</span>
                                <span class="font-bold text-slate-200 font-mono" id="plan-progress-text-{{ $up->id }}">${{ number_format($up->earned_so_far, 0, ',', '.') }} / ${{ number_format($up->max_earning, 0, ',', '.') }} COP</span>
                            </div>
                            <div class="w-full bg-slate-950 rounded-full h-2 overflow-hidden border border-slate-800">
                                @php
                                    $percent = $up->max_earning > 0 ? min(100, round(($up->earned_so_far / $up->max_earning) * 100)) : 0;
                                @endphp
                                <div id="plan-progress-bar-{{ $up->id }}" class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>

                        <div id="plan-action-container-{{ $up->id }}">
                            @if(!$up->canClaim())
                                <div class="w-full mt-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl flex items-center justify-between px-4 text-xs">
                                    <span class="text-slate-400">⏳ Próximo reclamo:</span>
                                    <span class="countdown-timer font-mono text-amber-400 font-extrabold" data-seconds="{{ $up->secondsUntilNextClaim() }}">Calculando...</span>
                                </div>
                            @else
                                <form method="POST" action="{{ route('cliente.plans.claim', $up->id) }}" onsubmit="handleClaimDaily(event, {{ $up->id }}, '{{ route('cliente.plans.claim', $up->id) }}')">
                                    @csrf
                                    <button type="submit" id="btn-claim-{{ $up->id }}" class="w-full mt-4 py-3.5 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-600 hover:to-teal-600 text-slate-950 text-xs font-black rounded-2xl shadow-lg shadow-emerald-500/25 transition active:scale-95 flex items-center justify-center gap-2 cursor-pointer animate-pulse">
                                        <span>🎁</span> Reclamar Ganancia de Hoy (+${{ number_format($up->daily_earning, 0, ',', '.') }} COP)
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 2. Catálogo de Planes Disponibles -->
    <div>
        <h2 class="text-sm font-extrabold text-white mb-3 flex items-center gap-2 px-1">
            <span>⭐</span> Paquetes Disponibles para Activar
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($availablePlans as $plan)
                <div class="bg-slate-900/70 border border-slate-800 hover:border-emerald-500/40 rounded-3xl p-5 flex flex-col justify-between transition-all duration-300">
                    <div>
                        @if($plan->badge)
                            <span class="px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-400 text-[10px] font-extrabold uppercase">
                                {{ $plan->badge }}
                            </span>
                        @endif
                        <h3 class="text-xl font-extrabold text-white mt-2">{{ $plan->name }}</h3>
                        <p class="text-xs text-slate-400 mt-1 min-h-[30px]">{{ $plan->description ?? 'Rendimiento diario fijo garantizado.' }}</p>

                        <div class="my-4 py-3 border-y border-slate-800/80">
                            <div class="text-2xl font-black text-white font-mono">
                                ${{ number_format($plan->price, 0, ',', '.') }} <span class="text-xs font-normal text-emerald-400 font-sans">COP</span>
                            </div>
                            <div class="text-xs text-emerald-400 font-bold mt-1">
                                Paga {{ $plan->daily_percentage }}% diario (${{ number_format(($plan->price * $plan->daily_percentage) / 100, 0, ',', '.') }} COP)
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                Tope máximo: <strong class="text-amber-400 font-mono">${{ number_format($plan->max_return, 0, ',', '.') }} COP</strong>
                            </div>
                        </div>
                    </div>

                    <form id="buy-plan-{{ $plan->id }}" method="POST" action="{{ route('cliente.plans.buy', $plan->id) }}">
                        @csrf
                        <button type="button" onclick="Swal.fire({
                            title: '¿Activar {{ $plan->name }}?',
                            html: 'Se descontarán <b class=\'text-emerald-400\'>${{ number_format($plan->price, 0, ',', '.') }} COP</b> de tu saldo disponible.<br><br><span class=\'text-xs text-slate-400\'>Rendimiento: <b>{{ $plan->daily_percentage }}% diario</b> durante <b>{{ $plan->duration_days }} días</b></span>',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#334155',
                            confirmButtonText: '⚡ Sí, Activar Plan',
                            cancelButtonText: 'Cancelar',
                            customClass: { popup: 'swal-custom-dark' }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('buy-plan-{{ $plan->id }}').submit();
                            }
                        })" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-emerald-500/20 transition cursor-pointer active:scale-95">
                            ⚡ Activar Este Plan
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

</div>

<script>
    async function handleClaimDaily(event, planId, url) {
        event.preventDefault();
        const btn = document.getElementById(`btn-claim-${planId}`);
        if (!btn || btn.disabled) return;

        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span>⏳ Acreditando saldo...</span>';

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
                    text: data.message || 'No se pudo procesar el reclamo.',
                    customClass: { popup: 'swal-custom-dark' },
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            // 1. Actualizar balance en el encabezado
            document.querySelectorAll('.user-balance-value').forEach(el => {
                el.innerText = `$${data.new_balance_formatted}`;
            });

            // 2. Actualizar progreso
            const progressText = document.getElementById(`plan-progress-text-${planId}`);
            if (progressText) {
                progressText.innerText = `$${data.earned_so_far_formatted} / $${data.max_earning_formatted} COP`;
            }
            const progressBar = document.getElementById(`plan-progress-bar-${planId}`);
            if (progressBar) {
                progressBar.style.width = `${data.percent}%`;
            }

            // 3. Reemplazar botón por temporizador sin alerta
            const container = document.getElementById(`plan-action-container-${planId}`);
            if (container) {
                if (data.status === 'completed') {
                    container.innerHTML = `
                        <div class="w-full mt-4 py-3 bg-slate-950 border border-emerald-500/30 rounded-2xl flex items-center justify-between px-4 text-xs">
                            <span class="text-emerald-400 font-bold">✅ Paquete Completado</span>
                            <span class="font-mono text-emerald-400 text-[10px]">100% Retorno</span>
                        </div>
                    `;
                } else {
                    container.innerHTML = `
                        <div class="w-full mt-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl flex items-center justify-between px-4 text-xs">
                            <span class="text-slate-400">⏳ Próximo reclamo:</span>
                            <span class="countdown-timer font-mono text-amber-400 font-extrabold" data-seconds="${data.next_seconds}">Calculando...</span>
                        </div>
                    `;
                    startCountdownTimers();
                }
            }

        } catch (err) {
            console.error('Error al reclamar:', err);
            const form = btn.closest('form');
            if (form) form.submit();
        }
    }

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
