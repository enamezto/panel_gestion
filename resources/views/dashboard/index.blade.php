@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

    {{-- Tarjetas de resumen --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Clientes totales --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500">Clientes totales</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalClientes ?? 0 }}</p>
        </div>

        {{-- Instancias activas --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500">Instancias activas</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalInstancias ?? 0 }}</p>
        </div>

        {{-- Clientes desactualizados --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500">Desactualizados</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $clientesDesactualizados ?? 0 }}</p>
        </div>

        {{-- Errores recientes --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500">Errores recientes</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $erroresRecientes ?? 0 }}</p>
        </div>

    </div>

    {{-- Tabla de clientes --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-800">Estado de clientes</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Cliente</th>
                        <th class="px-6 py-3 text-left font-medium">Versión a3ERP</th>
                        <th class="px-6 py-3 text-left font-medium">Instancias</th>
                        <th class="px-6 py-3 text-left font-medium">Estado</th>
                        <th class="px-6 py-3 text-left font-medium">Última conexión</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    {{-- Filas placeholder de momento --}}
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900">— Sin datos todavía —</td>
                        <td class="px-6 py-4 text-gray-600">—</td>
                        <td class="px-6 py-4 text-gray-600">—</td>
                        <td class="px-6 py-4">—</td>
                        <td class="px-6 py-4 text-gray-600">—</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
