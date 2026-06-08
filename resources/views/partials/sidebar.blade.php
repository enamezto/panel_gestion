<aside id="sidebar" class="bg-white text-gray-800 flex flex-col transition-all duration-300 w-64 border-r border-gray-200 overflow-hidden">
    {{-- Menú de navegación --}}
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="relative flex items-center min-h-[44px] px-3 py-2.5 rounded-lg text-sm font-medium
                  {{ request()->routeIs('dashboard') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            @if(request()->routeIs('dashboard'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-red-600 rounded-r"></span>
            @endif
            <x-heroicon-o-chart-bar class="w-5 h-5 flex-shrink-0" />
            <span class="ml-3 sidebar-text whitespace-nowrap">Dashboard</span>
        </a>

        {{-- Clientes --}}
        <a href="{{ route('clientes.index') }}"
            class="relative flex items-center min-h-[44px] px-3 py-2.5 rounded-lg text-sm font-medium
                {{ request()->routeIs('clientes.*') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            @if(request()->routeIs('clientes.*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-red-600 rounded-r"></span>
            @endif
            <x-heroicon-o-users class="w-5 h-5 flex-shrink-0" />
            <span class="ml-3 sidebar-text whitespace-nowrap">Clientes</span>
        </a>

        {{-- Catálogo --}}
        <a href="{{ route('catalogo.index') }}"
            class="relative flex items-center min-h-[44px] px-3 py-2.5 rounded-lg text-sm font-medium
                {{ request()->routeIs('catalogo.*') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            @if(request()->routeIs('catalogo.*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-red-600 rounded-r"></span>
            @endif
            <x-heroicon-o-cube class="w-5 h-5 flex-shrink-0" />
            <span class="ml-3 sidebar-text whitespace-nowrap">Catálogo</span>
        </a>

        {{-- Histórico --}}
        <a href="{{ route('historico.index') }}"
            class="relative flex items-center min-h-[44px] px-3 py-2.5 rounded-lg text-sm font-medium
                {{ request()->routeIs('historico.*') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            @if(request()->routeIs('historico.*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-red-600 rounded-r"></span>
            @endif
            <x-heroicon-o-clipboard-document-list class="w-5 h-5 flex-shrink-0" />
            <span class="ml-3 sidebar-text whitespace-nowrap">Histórico</span>
        </a>

    </nav>

    {{-- Pie del sidebar --}}
    <div class="px-6 py-4 border-t border-gray-200">
        <p class="text-xs text-gray-600">v1.0.0</p>
    </div>

</aside>

{{-- Script para colapsar/expandir el sidebar --}}
<script>
    document.getElementById('sidebar-toggle').addEventListener('click', function () {
        const sidebar = document.getElementById('sidebar');
        const texts = document.querySelectorAll('.sidebar-text');

        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-20');

        texts.forEach(text => text.classList.toggle('hidden'));
    });
</script>
