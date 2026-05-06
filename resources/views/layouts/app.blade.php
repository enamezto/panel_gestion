<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Gestión') - SDi</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Contenido principal --}}
        <div class="flex-1 flex flex-col">

            {{-- Cabecera --}}
            @include('partials.header')

            {{-- Contenido de cada página --}}
            <main class="flex-1 p-8">
                @yield('content')
            </main>

        </div>
    </div>
</body>
</html>
