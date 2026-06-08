@extends('layouts.app')

@section('title', 'Instancia - ' . $instancia->nombre)
@section('page_title', 'Detalle de Instancia')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Botón Volver --}}
    <div class="mb-6">
        <a href="{{ route('clientes.show', $cliente->id) }}"
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Volver a {{ $cliente->nombre }}
        </a>
    </div>

    {{-- Cabecera --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <x-heroicon-o-computer-desktop class="w-8 h-8 text-gray-400" />
                    <h1 class="text-3xl font-bold text-gray-900">{{ $instancia->nombre }}</h1>
                </div>
                <p class="text-gray-500 text-sm mt-1">Cliente: <span class="font-medium text-gray-700">{{ $cliente->nombre }}</span></p>
            </div>
            <div class="text-right space-y-1">
                <p class="text-sm text-gray-500">Última conexión</p>
                <p class="text-lg font-bold text-gray-800">
                    {{ $instancia->ultima_conexion ? \Carbon\Carbon::parse($instancia->ultima_conexion)->diffForHumans() : 'Sin conexión' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Datos técnicos --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
            <x-heroicon-o-information-circle class="w-5 h-5 mr-2 text-gray-500" />
            Datos técnicos
        </h3>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Host</p>
                <p class="font-mono text-gray-800 font-medium">{{ $instancia->host ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">IP</p>
                <p class="font-mono text-gray-800 font-medium">{{ $instancia->ip ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Tipo</p>
                <p class="text-gray-800 font-medium">{{ $instancia->tipo ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Ruta de listados</p>
                <p class="font-mono text-gray-800 font-medium text-sm">{{ $instancia->ruta_listados ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Historial de instalaciones --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 flex items-center">
                <x-heroicon-o-clock class="w-5 h-5 mr-2 text-gray-500" />
                Historial de instalaciones
            </h3>
            <span class="text-xs font-medium bg-gray-200 text-gray-600 px-2 py-1 rounded-full">
                {{ $historial->total() }} registros
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-bold">Fecha</th>
                        <th class="px-6 py-3 font-bold">Desarrollo</th>
                        <th class="px-6 py-3 font-bold">Versión</th>
                        <th class="px-6 py-3 font-bold text-center">Resultado</th>
                        <th class="px-6 py-3 font-bold">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($historial as $registro)
                        <tr>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                <div>{{ \Carbon\Carbon::parse($registro->fecha_actualizacion)->diffForHumans() }}</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($registro->fecha_actualizacion)->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $registro->version->desarrollo->nombre }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                    v{{ $registro->version->version_major }}.{{ $registro->version->version_minor }}.{{ $registro->version->version_patch }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($registro->resultado === 'ok')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        Ok
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        Error
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500 italic max-w-xs truncate" title="{{ $registro->observaciones }}">
                                {{ $registro->observaciones ?? 'Sin observaciones' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <x-heroicon-o-clock class="mx-auto h-12 w-12 text-gray-200 mb-3" />
                                <p class="text-sm italic">No hay registros de instalaciones para este equipo.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $historial->links() }}
        </div>
    </div>

</div>
@endsection
