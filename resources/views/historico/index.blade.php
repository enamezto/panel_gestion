@extends('layouts.app')

@section('title', 'Histórico')
@section('page_title', 'Historial de Instalaciones')

@section('content')

    {{-- Buscador y Filtros Rápidos --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form action="{{ route('historico.index') }}" method="GET" class="flex flex-wrap items-center gap-4">

            {{-- Buscador de texto (se mantiene igual) --}}
            <div class="flex-1 min-w-[300px]">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400" />
                    </div>
                    <input type="text"
                        name="buscar"
                        value="{{ $busqueda }}"
                        placeholder="Buscar por cliente, desarrollo o técnico..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>

            {{-- Filtro de Resultado con envío automático --}}
            <select name="resultado"
                    onchange="this.form.submit()" {{-- <--- Esto hace que al elegir, se envíe el formulario --}}
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Todos los resultados</option>
                <option value="ok" {{ $resultado == 'ok' ? 'selected' : '' }}>Éxitos</option>
                <option value="error" {{ $resultado == 'error' ? 'selected' : '' }}>Errores</option>
            </select>

        </form>
    </div>

    {{-- Tabla de Logs --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-xs uppercase text-gray-500 tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Fecha y Hora</th>
                        <th class="px-6 py-4 font-semibold">Cliente / Equipo (Instancia)</th>
                        <th class="px-6 py-4 font-semibold">Desarrollo Actualizado</th>
                        <th class="px-6 py-4 font-semibold text-center">Resultado</th>
                        <th class="px-6 py-4 font-semibold">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($registros as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Fecha humanizada y real --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($log->fecha_actualizacion)->diffForHumans() }}</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->fecha_actualizacion)->format('d/m/Y H:i') }}</div>
                            </td>

                            {{-- Cliente e Instancia --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $log->instancia->cliente->nombre }}</div>
                                <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                    <x-heroicon-o-computer-desktop class="w-3 h-3 mr-1" />
                                    {{ $log->instancia->nombre }}
                                </div>
                            </td>

                            {{-- Desarrollo y Versión --}}
                            <td class="px-6 py-4">
                                <div class="text-gray-900 font-medium">{{ $log->version->desarrollo->nombre }}</div>
                                <div class="text-xs font-mono text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded inline-block mt-1">
                                    v{{ $log->version->version_major }}.{{ $log->version->version_minor }}.{{ $log->version->version_patch }}
                                </div>
                            </td>

                            {{-- Badge de Resultado --}}
                            <td class="px-6 py-4 text-center">
                                @if($log->resultado === 'ok')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        Éxito
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        Error
                                    </span>
                                @endif
                            </td>

                            {{-- Observaciones --}}
                            <td class="px-6 py-4 text-gray-500 italic max-w-xs truncate" title="{{ $log->observaciones }}">
                                {{ $log->observaciones ?? 'Sin observaciones' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <x-heroicon-o-clipboard-document-list class="mx-auto h-12 w-12 text-gray-200 mb-3" />
                                No hay registros de instalaciones que coincidan con los filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación (Importante para el TFG) --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $registros->links() }}
        </div>
    </div>

@endsection
