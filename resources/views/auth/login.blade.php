<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Panel - SDi</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md px-6">

        {{-- Logo / Cabecera --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-600 rounded-2xl mb-4">
                <x-heroicon-o-cube class="w-8 h-8 text-white" />
            </div>
            <h1 class="text-2xl font-bold text-gray-900">SDi Panel</h1>
            <p class="text-gray-500 text-sm mt-1">Panel de Gestión Web · Acceso restringido</p>
        </div>

        {{-- Tarjeta de login --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            <h2 class="text-xl font-bold text-gray-800 mb-1">Iniciar sesión</h2>
            <p class="text-sm text-gray-500 mb-6">Introduce tus credenciales para acceder al panel.</p>

            {{-- Errores de validación --}}
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-sm font-medium text-red-700">Credenciales incorrectas</p>
                    </div>
                    <p class="text-xs text-red-600">Comprueba tu email y contraseña e inténtalo de nuevo.</p>
                </div>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-heroicon-o-envelope class="w-4 h-4 text-gray-400" />
                        </div>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               placeholder="Introduce el correo"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition {{ $errors->any() ? 'border-red-300' : '' }} [&:-webkit-autofill]:bg-white [&:-webkit-autofill]:shadow-[inset_0_0_0px_1000px_white]">
                    </div>
                </div>

                {{-- Contraseña --}}
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-heroicon-o-lock-closed class="w-4 h-4 text-gray-400" />
                        </div>
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition {{ $errors->any() ? 'border-red-300' : '' }} [&:-webkit-autofill]:bg-white [&:-webkit-autofill]:shadow-[inset_0_0_0px_1000px_white]">
                    </div>
                </div>

                {{-- Recordarme --}}
                <div class="flex items-center mb-6">
                    <input type="checkbox"
                           id="remember_me"
                           name="remember"
                           class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">
                        Recordar sesión
                    </label>
                </div>

                {{-- Botón de acceso --}}
                <button type="submit"
                        class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition focus:ring-2 focus:ring-red-600 focus:ring-offset-2">
                    Acceder al panel
                </button>

            </form>

        </div>

        {{-- Pie --}}
        <p class="text-center text-xs text-gray-500 mt-6">
            SDi Digital Group
        </p>

    </div>

</body>
</html>
