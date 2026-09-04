@extends('layouts.cliente')

@section('title', 'Membresías y Rendimientos')

@section('content')
<div class="space-y-6">

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white flex items-center gap-2">
                <span>⚡</span> Membresías y Rendimiento Diario
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">Activa paquetes y reclama tu porcentaje cada 24 horas.</p>
        </div>
        <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 sm:gap-2.5 w-full sm:w-auto">
            <div class="px-3 py-1.5 bg-slate-900/90 border border-slate-800 rounded-2xl text-center sm:text-left">
                <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold truncate">💳 Saldo Recargas</span>
                <p class="text-xs sm:text-sm font-black text-white font-mono">${{ number_format($rechargeBalance, 0, ',', '.') }}</p>
            </div>
            <div class="px-3 py-1.5 bg-slate-900/90 border border-emerald-500/30 rounded-2xl text-center sm:text-left">
                <span class="text-[10px] text-emerald-400 uppercase tracking-wider block font-semibold truncate">💎 Saldo Ganancias</span>
                <p class="text-xs sm:text-sm font-black text-emerald-400 font-mono">${{ number_format($earningsBalance, 0, ',', '.') }}</p>
            </div>
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
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                @if($plan->badge)
                                    <span class="px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-400 text-[10px] font-extrabold uppercase">
                                        {{ $plan->badge }}
                                    </span>
                                @endif
                            </div>
                            @if($plan->hasStockLimit())
                                @if($plan->isSoldOut())
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-400 border border-rose-500/30 text-[10px] font-extrabold uppercase">
                                        🔴 Agotado
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-bold">
                                        ⚡ Quedan {{ $plan->stock }} cupos
                                    </span>
                                @endif
                            @endif
                        </div>
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

                    <div>
                        @if($plan->isSoldOut())
                            <button type="button" disabled class="w-full py-3 bg-slate-800 text-slate-500 text-xs font-extrabold rounded-2xl border border-slate-700 cursor-not-allowed">
                                ❌ Agotado (Sin cupos)
                            </button>
                        @else
                            <button type="button" onclick="openBuyModal({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ $plan->price }}, '{{ number_format($plan->price, 0, ',', '.') }}', '{{ $plan->daily_percentage }}', '{{ $plan->duration_days }}')" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-emerald-500/20 transition cursor-pointer active:scale-95">
                                ⚡ Activar Este Plan
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Selección de Saldo para Comprar Plan -->
    <div id="chooseWalletModal" class="fixed inset-0 bg-black/85 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-4 sm:p-7 shadow-2xl relative text-xs space-y-4 max-h-[94vh] overflow-y-auto">
            <div class="flex items-start justify-between">
                <div>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-extrabold uppercase">Activar Membresía</span>
                    <h3 id="modalPlanName" class="text-lg font-black text-white mt-1">Nombre del Plan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Costo: <strong id="modalPlanPrice" class="text-emerald-400 font-mono text-sm">$0 COP</strong></p>
                </div>
                <button type="button" onclick="closeChooseWalletModal()" class="text-slate-400 hover:text-white text-base font-bold transition cursor-pointer">✕</button>
            </div>

            <p class="text-slate-300 text-xs font-semibold">
                ¿Con qué saldo deseas activar este plan? Elige una opción:
            </p>

            <form id="confirmBuyForm" method="POST" action="">
                @csrf
                <input type="hidden" name="payment_source" id="selectedPaymentSource" value="">

                <div class="space-y-2.5">
                    <!-- Opción 1: Saldo de Recargas -->
                    <div id="cardWalletDeposit" onclick="selectWallet('deposit')" class="p-3.5 bg-slate-950 border-2 border-slate-800 rounded-2xl cursor-pointer transition flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-lg">
                                💳
                            </div>
                            <div>
                                <h4 class="font-black text-white text-xs">Saldo de Recargas</h4>
                                <span class="text-[11px] text-slate-400 font-mono block">Disponible: ${{ number_format($rechargeBalance, 0, ',', '.') }} COP</span>
                            </div>
                        </div>
                        <div id="badgeWalletDeposit"></div>
                    </div>

                    <!-- Opción 2: Saldo de Ganancias -->
                    <div id="cardWalletEarnings" onclick="selectWallet('earnings')" class="p-3.5 bg-slate-950 border-2 border-slate-800 rounded-2xl cursor-pointer transition flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">
                                💎
                            </div>
                            <div>
                                <h4 class="font-black text-white text-xs">Saldo de Ganancias (Re-inversión)</h4>
                                <span class="text-[11px] text-emerald-400 font-mono block">Disponible: ${{ number_format($earningsBalance, 0, ',', '.') }} COP</span>
                            </div>
                        </div>
                        <div id="badgeWalletEarnings"></div>
                    </div>
                </div>

                <div id="walletErrorMsg" class="hidden mt-3 p-3 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-[11px]"></div>

                <div class="pt-3 space-y-2">
                    <button type="submit" id="btnConfirmBuy" disabled class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-slate-950 font-black rounded-2xl shadow-lg shadow-emerald-500/25 transition active:scale-95 text-xs sm:text-sm cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                        ⚡ Confirmar y Activar Plan
                    </button>
                    <button type="button" onclick="closeChooseWalletModal()" class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-2xl text-xs transition cursor-pointer">
                        Cancelar
                    </button>
                </div>
            </form>
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

    // Lógica del Modal de Selección de Saldo (Recarga vs Ganancias)
    const userRechargeBalance = {{ (float) $rechargeBalance }};
    const userEarningsBalance = {{ (float) $earningsBalance }};
    let currentPlanPrice = 0;

    function openBuyModal(planId, planName, planPrice, planPriceFormatted, dailyPercent, durationDays) {
        currentPlanPrice = parseFloat(planPrice);
        document.getElementById('modalPlanName').innerText = planName;
        document.getElementById('modalPlanPrice').innerText = '$' + planPriceFormatted + ' COP';
        document.getElementById('confirmBuyForm').action = "{{ url('/plans') }}/" + planId + "/buy";
        document.getElementById('selectedPaymentSource').value = '';
        document.getElementById('btnConfirmBuy').disabled = true;
        document.getElementById('walletErrorMsg').classList.add('hidden');

        // Evaluar disponibilidad de Saldo de Recargas
        const canDeposit = userRechargeBalance >= currentPlanPrice;
        const badgeDep = document.getElementById('badgeWalletDeposit');
        badgeDep.innerHTML = canDeposit 
            ? '<span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-extrabold">✅ Disponible</span>'
            : '<span class="px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-400 text-[10px] font-extrabold">❌ Insuficiente</span>';

        // Evaluar disponibilidad de Saldo de Ganancias
        const canEarnings = userEarningsBalance >= currentPlanPrice;
        const badgeEarn = document.getElementById('badgeWalletEarnings');
        badgeEarn.innerHTML = canEarnings 
            ? '<span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-extrabold">✅ Disponible</span>'
            : '<span class="px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-400 text-[10px] font-extrabold">❌ Insuficiente</span>';

        resetWalletCards();

        // Auto-seleccionar la opción disponible que tenga fondos
        if (canDeposit) {
            selectWallet('deposit');
        } else if (canEarnings) {
            selectWallet('earnings');
        } else {
            document.getElementById('walletErrorMsg').innerText = '⚠️ No tienes saldo suficiente en ninguna de las dos fuentes para este plan ($' + planPriceFormatted + ' COP). Por favor recarga saldo primero.';
            document.getElementById('walletErrorMsg').classList.remove('hidden');
        }

        document.getElementById('chooseWalletModal').classList.remove('hidden');
    }

    function closeChooseWalletModal() {
        document.getElementById('chooseWalletModal').classList.add('hidden');
    }

    function resetWalletCards() {
        const cardDep = document.getElementById('cardWalletDeposit');
        const cardEarn = document.getElementById('cardWalletEarnings');
        if (cardDep) {
            cardDep.className = 'p-3.5 bg-slate-950 border-2 border-slate-800 rounded-2xl cursor-pointer transition flex items-center justify-between hover:border-slate-700';
        }
        if (cardEarn) {
            cardEarn.className = 'p-3.5 bg-slate-950 border-2 border-slate-800 rounded-2xl cursor-pointer transition flex items-center justify-between hover:border-slate-700';
        }
    }

    function selectWallet(source) {
        const canPay = source === 'deposit' ? (userRechargeBalance >= currentPlanPrice) : (userEarningsBalance >= currentPlanPrice);
        const errorDiv = document.getElementById('walletErrorMsg');

        resetWalletCards();

        if (!canPay) {
            const label = source === 'deposit' ? 'Saldo de Recargas' : 'Saldo de Ganancias';
            errorDiv.innerText = `⚠️ Tu ${label} no alcanza para activar este plan ($${currentPlanPrice.toLocaleString('es-CO')} COP).`;
            errorDiv.classList.remove('hidden');
            document.getElementById('selectedPaymentSource').value = '';
            document.getElementById('btnConfirmBuy').disabled = true;
            return;
        }

        errorDiv.classList.add('hidden');
        document.getElementById('selectedPaymentSource').value = source;
        document.getElementById('btnConfirmBuy').disabled = false;

        if (source === 'deposit') {
            document.getElementById('cardWalletDeposit').className = 'p-3.5 bg-slate-950 border-2 border-emerald-500 shadow-lg shadow-emerald-500/10 rounded-2xl cursor-pointer transition flex items-center justify-between';
        } else {
            document.getElementById('cardWalletEarnings').className = 'p-3.5 bg-slate-950 border-2 border-emerald-500 shadow-lg shadow-emerald-500/10 rounded-2xl cursor-pointer transition flex items-center justify-between';
        }
    }

    // Cerrar modal al hacer clic en el backdrop
    document.getElementById('chooseWalletModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeChooseWalletModal();
        }
    });
</script>
@endsection
