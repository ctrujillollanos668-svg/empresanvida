<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Cuenta') - NVIDA.VIP</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/nvida.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #030712; }
        .bottom-nav-blur {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .swal2-popup.swal-custom-dark {
            background: #090d16 !important;
            border: 1px solid #1e293b !important;
            border-radius: 1.5rem !important;
            color: #f1f5f9 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75) !important;
        }
        .swal2-title {
            color: #ffffff !important;
            font-weight: 800 !important;
        }
        .swal2-html-container {
            color: #94a3b8 !important;
            font-size: 0.875rem !important;
        }
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 25s linear infinite;
        }
    </style>
</head>
<body class="text-slate-100 min-h-screen pb-20 lg:pb-8 selection:bg-emerald-500 selection:text-black">

    <!-- Luces Ambientales de Fondo -->
    <div class="fixed -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed top-1/2 -right-32 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <!-- Barra Superior Adaptable (Mobile + Portátil/Desktop) -->
    <header class="sticky top-0 z-40 bg-slate-950/85 border-b border-slate-800/80 backdrop-blur-xl">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-black border border-emerald-500/30 flex items-center justify-center overflow-hidden shadow-lg shadow-emerald-500/25">
                    <img src="{{ asset('img/nvida.jpg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <span class="text-sm sm:text-base font-extrabold text-white leading-none block">NVIDA<span class="text-emerald-400">.VIP</span></span>
                    <span class="text-[9px] text-emerald-400 font-bold uppercase tracking-wider hidden sm:inline">Servidor VIP</span>
                </div>
            </a>

            <!-- Navegación para Portátiles / Pantallas Grandes (Desktop Nav) -->
            <nav class="hidden lg:flex items-center gap-6 text-xs font-bold text-slate-300">
                <a href="{{ route('dashboard') }}" class="transition hover:text-emerald-400 {{ request()->routeIs('dashboard') ? 'text-emerald-400' : '' }}">
                    🏠 Inicio
                </a>
                <a href="{{ route('cliente.plans.index') }}" class="transition hover:text-emerald-400 {{ request()->routeIs('cliente.plans.*') ? 'text-emerald-400' : '' }}">
                    ⚡ Planes VIP
                </a>
                <a href="{{ route('cliente.deposits.index') }}" class="transition hover:text-emerald-400 {{ request()->routeIs('cliente.deposits.*') ? 'text-emerald-400' : '' }}">
                    ➕ Recargar
                </a>
                <a href="{{ route('cliente.team.index') }}" class="transition hover:text-emerald-400 {{ request()->routeIs('cliente.team.*') ? 'text-emerald-400' : '' }}">
                    👥 Mi Red (10%)
                </a>
                <a href="{{ route('cliente.withdrawals.index') }}" class="transition hover:text-cyan-400 {{ request()->routeIs('cliente.withdrawals.*') ? 'text-cyan-400' : '' }}">
                    💸 Retirar
                </a>
            </nav>

            <!-- Saldo y Botón Salir -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <!-- Chip de Saldo Rápido -->
                <a href="{{ route('cliente.deposits.index') }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-xl text-xs font-bold text-white hover:border-emerald-500/40 transition">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-emerald-400 font-mono font-black user-balance-value">${{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
                    <span class="text-[9px] text-slate-500">COP</span>
                </a>

                <!-- Botón Salir Moderno -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Cerrar Sesión" class="group flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-rose-500/15 border border-slate-800 hover:border-rose-500/30 text-slate-400 hover:text-rose-400 transition cursor-pointer text-xs font-semibold shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 group-hover:text-rose-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline text-[11px]">Salir</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Contenido Principal Adaptable -->
    <main class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pt-4 sm:pt-6">
        @if ($errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    <!-- BARRA DE NAVEGACIÓN INFERIOR PARA TELÉFONOS (SE OCULTA EN PORTÁTILES) -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-slate-950/90 border-t border-slate-800/90 bottom-nav-blur lg:hidden">
        <div class="max-w-md mx-auto px-6 h-16 flex items-center justify-between text-center">
            <!-- 1. Inicio -->
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 transition {{ request()->routeIs('dashboard') ? 'text-emerald-400 scale-105' : 'text-slate-400 hover:text-slate-200' }}">
                <span class="text-lg">🏠</span>
                <span class="text-[10px] font-bold">Inicio</span>
            </a>

            <!-- 2. Planes VIP -->
            <a href="{{ route('cliente.plans.index') }}" class="flex flex-col items-center gap-1 transition {{ request()->routeIs('cliente.plans.*') ? 'text-emerald-400 scale-105' : 'text-slate-400 hover:text-slate-200' }}">
                <span class="text-lg">⚡</span>
                <span class="text-[10px] font-bold">Planes</span>
            </a>

            <!-- 3. Recargar (Botón Central Destacado) -->
            <a href="{{ route('cliente.deposits.index') }}" class="-mt-5 w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-slate-950 flex items-center justify-center text-xl font-extrabold shadow-lg shadow-emerald-500/30 active:scale-95 transition">
                ➕
            </a>

            <!-- 4. Mi Equipo / Referidos -->
            <a href="{{ route('cliente.team.index') }}" class="flex flex-col items-center gap-1 transition {{ request()->routeIs('cliente.team.*') ? 'text-emerald-400 scale-105' : 'text-slate-400 hover:text-slate-200' }}">
                <span class="text-lg">👥</span>
                <span class="text-[10px] font-bold">Equipo</span>
            </a>

            <!-- 5. Retirar -->
            <a href="{{ route('cliente.withdrawals.index') }}" class="flex flex-col items-center gap-1 transition {{ request()->routeIs('cliente.withdrawals.*') ? 'text-cyan-400 scale-105' : 'text-slate-400 hover:text-slate-200' }}">
                <span class="text-lg">💸</span>
                <span class="text-[10px] font-bold">Retirar</span>
            </a>
        </div>
    </nav>

    <!-- SweetAlert2 Scripts para el Cliente -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: { popup: 'swal-custom-dark' }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Operación Exitosa!',
                text: "{{ session('success') }}",
                customClass: { popup: 'swal-custom-dark' },
                confirmButtonColor: '#10b981',
                confirmButtonText: '¡Excelente!'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Atención',
                text: "{{ session('error') }}",
                customClass: { popup: 'swal-custom-dark' },
                confirmButtonColor: '#f43f5e',
                confirmButtonText: 'Entendido'
            });
        @endif

        function notifyCopied(text = '¡Enlace copiado al portapapeles!') {
            Toast.fire({
                icon: 'success',
                title: text
            });
        }
    </script>
</body>
</html>
