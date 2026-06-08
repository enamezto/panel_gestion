@extends('layouts.app')

@section('title', 'Catálogo')
@section('page_title', 'Catálogo de Desarrollos')

@section('content')

    {{-- Cabecera con buscador y filtros --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form id="catalogo-form" method="GET" action="{{ route('catalogo.index') }}" class="flex flex-wrap items-center gap-4">

            <div class="flex-1 min-w-[300px]">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400" />
                    </div>
                    <input type="text"
                        name="buscar"
                        value="{{ $busqueda }}"
                        placeholder="Buscar desarrollo por nombre..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-600 focus:border-gray-600 text-sm outline-none">
                </div>
            </div>

            <input type="hidden" name="inactivos" id="inactivos-input" value="{{ $mostrarInactivos ? '1' : '0' }}">

            <button type="button"
                    onclick="toggleInactivos()"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition flex items-center gap-2
                    {{ $mostrarInactivos
                        ? 'bg-red-100 text-red-700 border border-red-200 hover:bg-red-200'
                        : 'bg-gray-100 text-gray-700 border border-gray-200 hover:bg-gray-200'
                    }}">
                @if($mostrarInactivos)
                    <x-heroicon-o-eye-slash class="w-4 h-4" />
                    Ocultar inactivos
                @else
                    <x-heroicon-o-eye class="w-4 h-4" />
                    Mostrar inactivos
                @endif
            </button>
        </form>

        <script>
            function toggleInactivos() {
                const input = document.getElementById('inactivos-input');
                const form = document.getElementById('catalogo-form');
                input.value = (input.value === '1') ? '0' : '1';
                form.submit();
            }
        </script>
    </div>

    {{-- Tabla del Catálogo --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <x-heroicon-o-cube class="w-5 h-5 mr-2 text-gray-500" />
                {{ count($desarrollos) }} {{ count($desarrollos) == 1 ? 'Desarrollo disponible' : 'Desarrollos disponibles' }}
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-xs uppercase text-gray-500 tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Desarrollo</th>
                        <th class="px-6 py-4 font-semibold text-center">Tipo</th>
                        <th class="px-6 py-4 font-semibold text-center">Última Versión</th>
                        <th class="px-6 py-4 font-semibold text-center">Compatibilidad a3ERP</th>
                        <th class="px-6 py-4 font-semibold text-center">Histórico</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($desarrollos as $dev)
                        <tr class="hover:bg-gray-100 transition-colors cursor-pointer {{ !$dev['activo'] ? 'opacity-60 bg-gray-50' : '' }}"
                            onclick="window.location='{{ route('catalogo.show', $dev['id']) }}'">

                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 flex items-center">
                                    {{ $dev['nombre'] }}
                                    @if(!$dev['activo'])
                                        <span class="ml-2 px-2 py-0.5 text-[10px] font-bold bg-red-100 text-red-700 rounded-full uppercase">Descatalogado</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1 max-w-xs" title="{{ $dev['descripcion'] }}">
                                    {{ $dev['descripcion'] ?? 'Sin descripción disponible' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200 uppercase tracking-wide">
                                    {{ $dev['tipo'] ?? 'Estándar' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="font-mono font-bold text-gray-800">{{ $dev['version_actual'] }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    {{ $dev['fecha_version'] ? \Carbon\Carbon::parse($dev['fecha_version'])->format('d/m/Y') : '' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($dev['version_actual'] !== '—')
                                    <div class="text-xs text-gray-600 bg-gray-100 inline-block px-2 py-1 rounded">
                                        {{ $dev['a3erp_min'] }} &rarr; {{ $dev['a3erp_max'] }}
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="text-gray-600 font-medium">
                                    {{ $dev['total_versiones'] }} {{ $dev['total_versiones'] == 1 ? 'versión' : 'versiones' }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-cube class="mx-auto h-12 w-12 text-gray-300 mb-3" />
                                @if($busqueda)
                                    No se han encontrado desarrollos que coincidan con "{{ $busqueda }}".
                                @else
                                    No hay desarrollos registrados en el catálogo.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
