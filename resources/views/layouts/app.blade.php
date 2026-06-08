<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Gestión') - SDi</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 font-sans antialiased h-screen flex flex-col overflow-hidden">

    {{-- Header arriba, ancho completo --}}
    @include('partials.header')

    {{-- Debajo: sidebar + contenido --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Contenido principal --}}
        <div class="flex-1 min-w-0 overflow-auto">
            <main class="p-8">
                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
