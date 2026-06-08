@extends('layouts.app')

@section('title', 'Catálogo - ' . $desarrollo->nombre)
@section('page_title', 'Detalle del Desarrollo')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Botón Volver --}}
    <div class="mb-6">
        <a href="{{ route('catalogo.index') }}"
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Volver al catálogo
        </a>
    </div>

    {{-- Cabecera del desarrollo --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $desarrollo->nombre }}</h1>
                    @if($desarrollo->activo)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">Activo</span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full uppercase">Descatalogado</span>
                    @endif
                </div>
                <p class="text-gray-500 text-sm mt-1">{{ $desarrollo->descripcion ?? 'Sin descripción disponible' }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200 uppercase tracking-wide">
                    {{ $desarrollo->tipo }}
                </span>
                @if($versionVigente)
                    <p class="text-sm text-gray-500 mt-2">Versión actual</p>
                    <p class="text-2xl font-bold text-gray-800 font-mono">
                        v{{ $versionVigente->version_major }}.{{ $versionVigente->version_minor }}.{{ $versionVigente->version_patch }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Columna izquierda: Historial de versiones --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Historial de versiones --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <x-heroicon-o-clock class="w-5 h-5 mr-2 text-gray-500" />
                        Historial de versiones
                    </h3>
                    <span class="text-xs font-medium bg-gray-200 text-gray-600 px-2 py-1 rounded-full">
                        {{ $desarrollo->versiones->count() }} {{ $desarrollo->versiones->count() == 1 ? 'versión' : 'versiones' }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-wider border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 font-bold">Versión</th>
                                <th class="px-6 py-3 font-bold">Fecha</th>
                                <th class="px-6 py-3 font-bold">Descripción</th>
                                <th class="px-6 py-3 font-bold text-center">Requiere parada</th>
                                <th class="px-6 py-3 font-bold text-center">Compatibilidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($desarrollo->versiones as $version)
                                @php
                                    $esActual = $versionVigente && $version->id === $versionVigente->id;
                                @endphp
                                <tr class="{{ $esActual ? 'bg-gray-100' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono font-bold text-gray-900">
                                                v{{ $version->version_major }}.{{ $version->version_minor }}.{{ $version->version_patch }}
                                            </span>
                                            @if($esActual)
                                                <span class="text-[10px] bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full font-bold uppercase">
                                                    Actual
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($version->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs">
                                        <span class="line-clamp-2" title="{{ $version->descripcion_cambios }}">
                                            {{ $version->descripcion_cambios ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($version->requiere_parada)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700">
                                                Sí
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-700">
                                                No
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($version->version_a3erp_min || $version->version_a3erp_max)
                                            <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded font-mono">
                                                {{ $version->version_a3erp_min ?? '—' }} → {{ $version->version_a3erp_max ?? '∞' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Columna derecha: Clientes con licencia --}}
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <x-heroicon-o-users class="w-5 h-5 mr-2 text-gray-500" />
                        Clientes con licencia
                    </h3>
                    <span class="text-xs font-medium bg-gray-200 text-gray-600 px-2 py-1 rounded-full">
                        {{ $clientesConLicencia->count() }}
                    </span>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($clientesConLicencia as $licencia)
                        @php
                            $versionInstalada = $licencia->estadoActual?->version;
                            $desactualizado = $versionVigente && $versionInstalada && $versionInstalada->id !== $versionVigente->id;
                        @endphp
                        <a href="{{ route('clientes.show', $licencia->cliente->id) }}"
                        class="block px-6 py-4 hover:bg-gray-100 transition cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">
                                        {{ $licencia->cliente->nombre }}
                                    </p>
                                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                                        {{ $licencia->cliente->codigo }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($versionInstalada)
                                        <span class="font-mono text-xs {{ $desactualizado ? 'text-orange-600 font-bold' : 'text-gray-600' }}">
                                            v{{ $versionInstalada->version_major }}.{{ $versionInstalada->version_minor }}.{{ $versionInstalada->version_patch }}
                                        </span>
                                        @if($desactualizado)
                                            <div class="text-[10px] text-orange-500 font-bold uppercase mt-0.5">
                                                Desactualizado
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">Sin datos</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-400">
                            <x-heroicon-o-users class="mx-auto h-8 w-8 text-gray-300 mb-2" />
                            <p class="text-sm italic">Ningún cliente tiene este desarrollo contratado</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
