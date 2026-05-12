@extends('layouts.app')

@section('title', 'Clientes')
@section('page_title', 'Listado de Clientes')

@section('content')

{{-- Cabecera con buscador, filtro y botón --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    {{-- Formulario con ID para poder enviarlo desde el botón --}}
    <form id="filter-form" method="GET" action="{{ route('clientes.index') }}" class="flex flex-wrap items-center gap-4">

        {{-- Buscador (se mantiene igual) --}}
        <div class="flex-1 min-w-[300px]">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400" />
                </div>
                <input type="text" name="buscar" value="{{ $busqueda }}" placeholder="Buscar por nombre..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>

        {{-- Input oculto para mantener el estado de "inactivos" --}}
        <input type="hidden" name="inactivos" id="inactivos-input" value="{{ $mostrarInactivos ? '1' : '0' }}">

        {{-- Botón dinámico que actúa como interruptor --}}
        <button type="button"
            onclick="toggleInactivos()"
            class="px-4 py-2 text-sm font-medium rounded-lg transition flex items-center gap-2
            {{ $mostrarInactivos
                ? 'bg-orange-100 text-orange-700 border border-orange-200 hover:bg-orange-200'
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

        {{-- Botón crear cliente --}}
        <a href="#" class="ml-auto inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
            <x-heroicon-o-plus class="w-4 h-4" />
            Crear cliente
        </a>
    </form>

{{-- Script para cambiar el valor y enviar --}}
<script>
    function toggleInactivos() {
        const input = document.getElementById('inactivos-input');
        const form = document.getElementById('filter-form');

        // Si es 0 (falso), lo ponemos a 1. Si es 1, lo ponemos a 0.
        input.value = (input.value === '1') ? '0' : '1';

        // Enviamos el formulario automáticamente
        form.submit();
    }
</script>
    </div>

    {{-- Tabla de clientes --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">
                {{ count($clientes) }} {{ count($clientes) == 1 ? 'cliente' : 'clientes' }}
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-xs uppercase text-gray-500 tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Nombre</th>
                        <th class="px-6 py-4 font-semibold">Código</th>
                        <th class="px-6 py-4 font-semibold">Versión a3ERP</th>
                        <th class="px-6 py-4 font-semibold text-center">Nº Instancias</th>
                        <th class="px-6 py-4 font-semibold text-center">Estado</th>
                        <th class="px-6 py-4 font-semibold">Última Conexión</th>
                        <th class="px-6 py-4 font-semibold text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($clientes as $cliente)
                        <tr class="hover:bg-gray-50 transition-colors {{ !$cliente['activo'] ? 'opacity-50' : '' }}">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $cliente['nombre'] }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $cliente['id'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-mono text-xs">
                                {{ $cliente['codigo'] }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $cliente['version_a3erp'] ? 'v' . $cliente['version_a3erp'] : '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $cliente['instancias'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($cliente['estado'] === 'inactivo')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                        <span class="w-2 h-2 rounded-full bg-gray-500 mr-2"></span>
                                        Inactivo
                                    </span>
                                @elseif($cliente['estado'] === 'actualizado')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                        Actualizado
                                    </span>
                                @elseif($cliente['estado'] === 'desactualizado')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                        <span class="w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
                                        Requiere Actualización
                                    </span>
                                @elseif($cliente['estado'] === 'error')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                        Fallo Reciente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $cliente['ultima_conexion'] ? \Carbon\Carbon::parse($cliente['ultima_conexion'])->diffForHumans() : 'Sin conexión' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('clientes.show', $cliente['id']) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors">
                                    Ver Ficha
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-inbox class="mx-auto h-12 w-12 text-gray-300 mb-3" />
                                @if($busqueda)
                                    No se han encontrado clientes con el nombre "{{ $busqueda }}".
                                @else
                                    No hay clientes registrados todavía.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
