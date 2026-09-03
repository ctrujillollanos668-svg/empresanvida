<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - NVIDA.VIP</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/nvida.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #030712; }
    </style>
</head>
<body class="text-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Luces de fondo -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md bg-slate-900/90 border border-slate-800 backdrop-blur-2xl rounded-3xl p-6 sm:p-8 shadow-2xl relative z-10 my-6">
        
        <!-- Logo / Volver -->
        <div class="flex items-center justify-between mb-5">
            <a href="/" class="text-xs text-slate-400 hover:text-emerald-400 flex items-center gap-1 transition">
                ← Volver al Inicio
            </a>
            <div class="w-8 h-8 rounded-xl bg-black border border-emerald-500/30 flex items-center justify-center overflow-hidden shadow-md">
                <img src="{{ asset('img/nvida.jpg') }}" alt="Logo" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Pestañas de Cambio Rápido entre Login y Registro sin salir de la página -->
        <div class="grid grid-cols-2 p-1 bg-slate-950 rounded-2xl border border-slate-800 mb-6 text-xs font-bold">
            <button type="button" id="tabLoginBtn" onclick="switchAuthTab('login')" class="py-2.5 rounded-xl transition cursor-pointer bg-emerald-500 text-slate-950 font-bold shadow-md">
                🔑 Iniciar Sesión
            </button>
            <button type="button" id="tabRegisterBtn" onclick="switchAuthTab('register')" class="py-2.5 rounded-xl transition cursor-pointer text-slate-400 hover:text-white">
                🚀 Registrarse
            </button>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3.5 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-400 text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORMULARIO 1: INICIAR SESIÓN -->
        <div id="loginFormSection">
            <form method="POST" action="{{ route('login') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Correo Electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="usuario@ejemplo.com" class="w-full px-4 py-3 bg-slate-950/70 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Contraseña</label>
                    <input id="password" type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-950/70 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div class="flex items-center gap-2 text-slate-400">
                    <input type="checkbox" name="remember" id="remember" class="rounded bg-slate-950 border-slate-800 text-emerald-500 focus:ring-emerald-500">
                    <label for="remember" class="cursor-pointer">Recordarme en este dispositivo</label>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/25 transition active:scale-95 text-xs sm:text-sm cursor-pointer">
                    Iniciar Sesión
                </button>
            </form>
        </div>

        <!-- FORMULARIO 2: REGISTRO EN LA MISMA VENTANA -->
        <div id="registerFormSection" class="hidden">
            <form method="POST" action="{{ route('register') }}" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-300 uppercase tracking-wider mb-1">Nombre Completo</label>
                    <input type="text" name="name" required placeholder="Ej: Carlos Trujillo" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 uppercase tracking-wider mb-1">Correo Electrónico</label>
                    <input type="email" name="email" required placeholder="tu@correo.com" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 uppercase tracking-wider mb-1">Celular / WhatsApp</label>
                    <input type="text" name="phone" placeholder="300 1234567" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 uppercase tracking-wider mb-1 flex items-center justify-between">
                        <span>Código de Invitación / Referido</span>
                        <span class="text-[10px] text-emerald-400 font-normal">Opcional</span>
                    </label>
                    <input id="ref_code_input" type="text" name="referral_code" value="{{ request('ref') }}" placeholder="Ej: JUANVIP" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-emerald-400 font-mono uppercase focus:outline-none focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-semibold text-slate-300 uppercase tracking-wider mb-1">Contraseña</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 uppercase tracking-wider mb-1">Confirmar</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/25 transition active:scale-95 text-xs sm:text-sm cursor-pointer">
                    Completar Registro
                </button>
            </form>
        </div>

    </div>

    <script>
        function switchAuthTab(tab) {
            const loginSection = document.getElementById('loginFormSection');
            const registerSection = document.getElementById('registerFormSection');
            const tabLoginBtn = document.getElementById('tabLoginBtn');
            const tabRegisterBtn = document.getElementById('tabRegisterBtn');

            if (tab === 'login') {
                loginSection.classList.remove('hidden');
                registerSection.classList.add('hidden');
                tabLoginBtn.className = "py-2.5 rounded-xl transition cursor-pointer bg-emerald-500 text-slate-950 font-bold shadow-md";
                tabRegisterBtn.className = "py-2.5 rounded-xl transition cursor-pointer text-slate-400 hover:text-white";
            } else {
                loginSection.classList.add('hidden');
                registerSection.classList.remove('hidden');
                tabRegisterBtn.className = "py-2.5 rounded-xl transition cursor-pointer bg-emerald-500 text-slate-950 font-bold shadow-md";
                tabLoginBtn.className = "py-2.5 rounded-xl transition cursor-pointer text-slate-400 hover:text-white";
            }
        }

        function fillCreds(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
        }

        // Si la URL tiene ?register=1 o ?ref=..., activar pestaña de registro automáticamente
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('register') || urlParams.get('ref')) {
            switchAuthTab('register');
        }
    </script>
</body>
</html>
