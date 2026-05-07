@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Estado General del Sistema')

@section('content')

    {{-- Tarjetas de KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Clientes activos --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col transition hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Clientes Activos</h3>
                <div class="p-2 bg-blue-50 rounded-lg">
                    <x-heroicon-o-users class="w-6 h-6 text-blue-500" />
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalClientes }}</p>
        </div>

        {{-- Instancias --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col transition hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Instancias (PCs)</h3>
                <div class="p-2 bg-indigo-50 rounded-lg">
                    <x-heroicon-o-computer-desktop class="w-6 h-6 text-indigo-500" />
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalInstancias }}</p>
        </div>

        {{-- Clientes desactualizados --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col transition hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Desactualizados</h3>
                <div class="p-2 bg-orange-50 rounded-lg">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-orange-500" />
                </div>
            </div>
            <p class="text-3xl font-bold {{ $clientesDesactualizados > 0 ? 'text-orange-600' : 'text-gray-800' }}">
                {{ $clientesDesactualizados }}
            </p>
        </div>

        {{-- Errores recientes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col transition hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Errores (7 días)</h3>
                <div class="p-2 bg-red-50 rounded-lg">
                    <x-heroicon-o-x-circle class="w-6 h-6 text-red-500" />
                </div>
            </div>
            <p class="text-3xl font-bold {{ $erroresRecientes > 0 ? 'text-red-600' : 'text-gray-800' }}">
                {{ $erroresRecientes }}
            </p>
        </div>

    </div>

    {{-- Tabla de clientes --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Monitorización de Clientes</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-xs uppercase text-gray-500 tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Cliente</th>
                        <th class="px-6 py-4 font-semibold">Versión a3ERP</th>
                        <th class="px-6 py-4 font-semibold text-center">Nº Instancias</th>
                        <th class="px-6 py-4 font-semibold">Última Conexión</th>
                        <th class="px-6 py-4 font-semibold text-center">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($clientes as $cliente)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $cliente['nombre'] }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $cliente['id'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $cliente['version_a3erp'] ? 'v' . $cliente['version_a3erp'] : '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $cliente['instancias'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $cliente['ultima_conexion'] ? \Carbon\Carbon::parse($cliente['ultima_conexion'])->diffForHumans() : 'Sin conexión' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($cliente['estado'] === 'actualizado')
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
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-blue-600 hover:text-blue-900 font-medium text-sm transition">
                                    Ver Ficha &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-inbox class="mx-auto h-12 w-12 text-gray-300 mb-3" />
                                No hay clientes registrados o activos en el sistema en este momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
