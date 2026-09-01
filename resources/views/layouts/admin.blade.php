<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - PYRAMID VIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col md:flex-row">

    <!-- BARRA SUPERIOR MÓVIL CON MENÚ HAMBURGUESA (SOLO EN TELÉFONOS) -->
    <div class="md:hidden bg-slate-900/95 border-b border-slate-800 p-4 flex items-center justify-between sticky top-0 z-50 backdrop-blur-xl">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-amber-500 to-yellow-400 flex items-center justify-center text-slate-950 text-base font-black shadow-md">
                👑
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-white leading-none">ADMIN PANEL</h2>
                <span class="text-[9px] text-amber-400 font-bold tracking-wider uppercase">Super Admin</span>
            </div>
        </div>

        <button onclick="toggleAdminMenu()" class="p-2 bg-slate-950 border border-slate-800 rounded-xl text-slate-300 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <!-- SIDEBAR LATERAL (DESKTOP FIJO & MÓVIL COLAPSABLE) -->
    <aside id="adminSidebar" class="hidden md:flex w-full md:w-64 bg-slate-900/90 border-r border-slate-800 flex-shrink-0 flex-col justify-between p-4 z-40 sticky top-0 md:h-screen overflow-y-auto">
        <div>
            <!-- Logo Admin en Desktop -->
            <div class="hidden md:flex items-center gap-3 px-2 py-4 mb-4 border-b border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-yellow-400 flex items-center justify-center text-slate-950 text-xl font-black shadow-lg shadow-amber-500/20">
                    👑
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-white leading-tight">ADMIN PANEL</h2>
                    <span class="text-[10px] text-amber-400 font-bold tracking-wider uppercase">Super Administrador</span>
                </div>
            </div>

            <!-- Navegación -->
            <nav class="space-y-1.5 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500/15 text-amber-400 border border-amber-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">📊</span>
                    <span>Dashboard General</span>
                </a>

                <a href="{{ route('admin.deposits.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.deposits.*') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-base">📥</span>
                        <span>Depósitos / Recargas</span>
                    </div>
                    @php $pendingDeps = \App\Models\Deposit::where('status', 'pending')->count(); @endphp
                    @if($pendingDeps > 0)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500 text-slate-950 text-[10px] font-extrabold">{{ $pendingDeps }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.withdrawals.*') ? 'bg-cyan-500/15 text-cyan-400 border border-cyan-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-base">📤</span>
                        <span>Solicitudes de Retiro</span>
                    </div>
                    @php $pendingWiths = \App\Models\Withdrawal::where('status', 'pending')->count(); @endphp
                    @if($pendingWiths > 0)
                        <span class="px-2 py-0.5 rounded-full bg-cyan-500 text-slate-950 text-[10px] font-extrabold">{{ $pendingWiths }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.plans.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.plans.*') ? 'bg-purple-500/15 text-purple-400 border border-purple-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">📦</span>
                    <span>Planes y Membresías</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-amber-500/15 text-amber-400 border border-amber-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">👥</span>
                    <span>Gestión de Clientes</span>
                </a>

                <a href="{{ route('admin.payment-methods.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.payment-methods.*') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">💳</span>
                    <span>Cuentas de Pago & QR</span>
                </a>

                <a href="{{ route('dashboard') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition">
                    <span class="text-base">🌐</span>
                    <span>Ver Vista de Cliente</span>
                </a>
            </nav>
        </div>

        <!-- Usuario Logueado & Salir -->
        <div class="pt-4 border-t border-slate-800/80 mt-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-400 truncate max-w-[120px]">{{ Auth::user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Cerrar Sesión" class="group flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-950 hover:bg-rose-500/15 border border-slate-800 hover:border-rose-500/30 text-slate-400 hover:text-rose-400 transition cursor-pointer text-xs font-semibold shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 group-hover:text-rose-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Salir</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- CONTENIDO PRINCIPAL ADAPTABLE -->
    <main class="flex-1 p-3 sm:p-6 lg:p-8 overflow-y-auto max-h-screen">
        @if ($errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    <!-- SweetAlert2 Scripts para Admin -->
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
                confirmButtonText: 'Continuar'
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

        function toggleAdminMenu() {
            const sidebar = document.getElementById('adminSidebar');
            sidebar.classList.toggle('hidden');
        }

        // Función Global de Confirmación SweetAlert2 para Formularios
        function confirmCustomAction(options) {
            Swal.fire({
                title: options.title || '¿Estás seguro?',
                html: options.html || 'Esta acción no se puede deshacer.',
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: options.confirmColor || '#f43f5e',
                cancelButtonColor: '#334155',
                confirmButtonText: options.confirmText || 'Sí, Continuar',
                cancelButtonText: 'Cancelar',
                customClass: { popup: 'swal-custom-dark' }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (options.formId) {
                        const form = document.getElementById(options.formId);
                        if (form) {
                            form.submit();
                        }
                    } else if (typeof options.onConfirm === 'function') {
                        options.onConfirm();
                    }
                }
            });
        }
    </script>
</body>
</html>
