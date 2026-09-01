<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cliente - Dashboard VIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen pb-16">
    <!-- Barra Superior -->
    <header class="bg-slate-900/80 border-b border-slate-800 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl">💎</span>
                <div>
                    <h1 class="text-base font-bold text-white leading-tight">Plataforma VIP</h1>
                    <p class="text-xs text-emerald-400 font-medium">Panel de Miembro</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-semibold text-white">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400">{{ $user->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-lg border border-slate-700 transition cursor-pointer">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 pt-6 space-y-6">
        <!-- 1. Tarjeta Principal de Balance -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-emerald-950/40 border border-slate-800 rounded-3xl p-6 sm:p-8 relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Saldo Disponible</span>
                    <div class="text-4xl sm:text-5xl font-extrabold text-white mt-1 mb-2">
                        ${{ number_format($user->balance, 2) }} <span class="text-sm font-normal text-slate-400">USD</span>
                    </div>
                    <p class="text-xs text-slate-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Cuenta activa • Código: <strong class="text-emerald-400 font-mono">{{ $user->referral_code }}</strong>
                    </p>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-3 md:justify-end">
                    <button onclick="alert('Módulo de recarga: En la siguiente etapa conectaremos la pasarela o billetera.')" class="w-full sm:w-auto px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-xl text-sm shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <span>➕</span> Recargar Saldo
                    </button>
                    <button onclick="alert('Módulo de retiro: En la siguiente etapa podrás ingresar monto y billetera de destino.')" class="w-full sm:w-auto px-5 py-3 bg-slate-800/80 hover:bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl text-sm transition flex items-center justify-center gap-2 cursor-pointer">
                        <span>💸</span> Solicitar Retiro
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. Enlace de Referidos (Copia Rápida) -->
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 backdrop-blur-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <span>🔗</span> Tu Enlace de Invitación (Red de Referidos)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Comparte este link para ganar comisiones por cada persona que se registre y active un plan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <input id="refLink" type="text" readonly value="{{ url('/register?ref=' . $user->referral_code) }}"
                        class="bg-slate-950 border border-slate-800 text-xs text-emerald-400 font-mono px-3 py-2 rounded-xl w-full sm:w-72 select-all focus:outline-none">
                    <button onclick="copyRefLink()" class="px-4 py-2 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/40 text-emerald-400 text-xs font-bold rounded-xl transition whitespace-nowrap cursor-pointer">
                        Copiar Link
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-800/60">
                <div class="bg-slate-950/40 rounded-xl p-3 border border-slate-800/60">
                    <p class="text-xs text-slate-400">Referidos Directos</p>
                    <p class="text-lg font-bold text-white mt-0.5">{{ $referralsCount }} personas</p>
                </div>
                <div class="bg-slate-950/40 rounded-xl p-3 border border-slate-800/60">
                    <p class="text-xs text-slate-400">Comisiones Ganadas</p>
                    <p class="text-lg font-bold text-emerald-400 mt-0.5">${{ number_format($totalCommissions, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- 3. Tus Planes Activos -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>⚡</span> Tus Planes Activos
                </h2>
                <span class="text-xs text-slate-400">{{ $userPlans->count() }} plan(es) en curso</span>
            </div>

            @if($userPlans->isEmpty())
                <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-8 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto text-xl mb-3">
                        📦
                    </div>
                    <h3 class="text-sm font-bold text-slate-300">No tienes ningún plan activo aún</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Selecciona uno de los paquetes VIP disponibles abajo para comenzar a generar rendimiento diario.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($userPlans as $up)
                        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 relative overflow-hidden">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">Activo</span>
                                    <h4 class="text-base font-bold text-white mt-1">{{ $up->plan->name }}</h4>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-slate-400">Rendimiento</span>
                                    <p class="text-sm font-bold text-emerald-400">+${{ number_format($up->daily_earning, 2) }} / día</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="flex justify-between text-xs text-slate-400 mb-1.5">
                                    <span>Progreso del Tope:</span>
                                    <span class="font-bold text-slate-200">${{ number_format($up->earned_so_far, 2) }} / ${{ number_format($up->max_earning, 2) }}</span>
                                </div>
                                <div class="w-full bg-slate-950 rounded-full h-2 overflow-hidden border border-slate-800">
                                    @php
                                        $percent = $up->max_earning > 0 ? min(100, round(($up->earned_so_far / $up->max_earning) * 100)) : 0;
                                    @endphp
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full transition-all" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>

                            <button onclick="alert('Reclamo diario: En el siguiente controlador sumará la ganancia del día.')" class="w-full mt-4 py-2.5 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-xl transition cursor-pointer">
                                🎁 Reclamar Ganancia de Hoy
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 4. Catálogo de Planes Disponibles -->
        <div>
            <h2 class="text-lg font-bold text-white flex items-center gap-2 mb-4">
                <span>⭐</span> Planes Disponibles
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($availablePlans as $plan)
                    <div class="bg-slate-900/60 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 flex flex-col justify-between transition-all">
                        <div>
                            @if($plan->badge)
                                <span class="px-2.5 py-0.5 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $plan->badge }}
                                </span>
                            @endif
                            <h3 class="text-lg font-bold text-white mt-2">{{ $plan->name }}</h3>
                            <p class="text-xs text-slate-400 mt-1">{{ $plan->description }}</p>

                            <div class="my-4 py-3 border-y border-slate-800/80">
                                <div class="text-2xl font-extrabold text-white">
                                    ${{ number_format($plan->price, 2) }} <span class="text-xs font-normal text-slate-400">USD</span>
                                </div>
                                <div class="text-xs text-emerald-400 font-semibold mt-1">
                                    Paga {{ $plan->daily_percentage }}% diario ({{ $plan->duration_days }} días)
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    Tope máximo: <strong>${{ number_format($plan->max_return, 2) }}</strong>
                                </div>
                            </div>
                        </div>

                        <button onclick="alert('Activación de plan: En el siguiente controlador debitará ${{ $plan->price }} del saldo del usuario.')" class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer">
                            Activar Plan
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <script>
        function copyRefLink() {
            const input = document.getElementById('refLink');
            input.select();
            navigator.clipboard.writeText(input.value);
            alert('¡Enlace de referido copiado al portapapeles!');
        }
    </script>
</body>
</html>
