<header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">

    {{-- Título de la página actual --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h2>
    </div>

    {{-- Usuario --}}
    <div class="flex items-center space-x-3">
        <span class="text-sm text-gray-600">Técnico de Sistemas</span>
        <div class="w-9 h-9 rounded-full bg-gray-700 text-white flex items-center justify-center text-sm font-medium">
            T
        </div>
    </div>

</header>
