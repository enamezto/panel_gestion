<header class="bg-black px-8 py-5 flex items-center justify-between gap-8">

    {{-- Logo / título --}}
    <div class="flex-shrink-0">
        <h1 class="text-4xl font-bold text-white">SDi Panel</h1>
        <p class="text-s text-gray-400">Gestión a3ERP</p>
    </div>

    {{-- Buscador central --}}
    <div class="flex-1 max-w-xl relative" id="search-container">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400" />
            </div>
            <input type="text"
                id="search-input"
                placeholder="Buscar cliente, desarrollo..."
                class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 text-gray-900 placeholder-gray-400 rounded-lg text-sm focus:ring-1 focus:ring-red-700 focus:border-red-700 outline-none transition"
                autocomplete="off">
        </div>

        {{-- Dropdown de resultados --}}
        <div id="search-dropdown"
            class="absolute top-full left-0 right-0 mt-1 bg-white rounded-xl shadow-lg border border-gray-200 z-50 hidden">
        </div>
    </div>

    <script>
        const input = document.getElementById('search-input');
        const dropdown = document.getElementById('search-dropdown');
        let timeout;

        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const q = this.value.trim();

            if (q.length < 2) {
                dropdown.classList.add('hidden');
                dropdown.innerHTML = '';
                return;
            }

            timeout = setTimeout(() => {
                fetch(`/buscar?q=${encodeURIComponent(q)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.clientes.length === 0 && data.desarrollos.length === 0) {
                            dropdown.innerHTML = `
                                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                    Sin resultados para "${q}"
                                </div>`;
                            dropdown.classList.remove('hidden');
                            return;
                        }

                        let html = '';

                        if (data.clientes.length > 0) {
                            html += `<div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">Clientes</div>`;
                            data.clientes.forEach(c => {
                                html += `
                                    <a href="/clientes/${c.id}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">${c.nombre}</p>
                                            <p class="text-xs text-gray-500 font-mono">${c.codigo}</p>
                                        </div>
                                    </a>`;
                            });
                        }

                        if (data.desarrollos.length > 0) {
                            html += `<div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 ${data.clientes.length > 0 ? 'border-t mt-1' : ''}">Desarrollos</div>`;
                            data.desarrollos.forEach(d => {
                                html += `
                                    <a href="/catalogo/${d.id}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">${d.nombre}</p>
                                            <p class="text-xs text-gray-500">${d.tipo}</p>
                                        </div>
                                    </a>`;
                            });
                        }

                        dropdown.innerHTML = html;
                        dropdown.classList.remove('hidden');
                    });
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('search-container').contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    {{-- Usuario y logout --}}
    <div class="flex items-center space-x-4 flex-shrink-0">
        <span class="text-sm text-gray-300">{{ Auth::user()->name }}</span>
        <div class="w-9 h-9 rounded-full bg-gray-700 text-white flex items-center justify-center text-sm font-medium">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-400 hover:bg-gray-800 rounded-lg transition">
                <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                Salir
            </button>
        </form>
    </div>

</header>
