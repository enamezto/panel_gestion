@extends('layouts.app')

@section('title', 'Ficha de Cliente - ' . $cliente->nombre)
@section('page_title', 'Detalle del Cliente')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Botón Volver --}}
    <div class="mb-6">
        <a href="{{ route('clientes.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Volver al listado
        </a>
    </div>

    {{-- Cabecera de la Ficha --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $cliente->nombre }}</h1>
                    @if($cliente->activo)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">Activo</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full uppercase">Inactivo</span>
                    @endif
                </div>
                <p class="text-gray-500 font-mono italic text-sm">Código a3ERP: {{ $cliente->codigo }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Versión a3ERP</p>
                <p class="text-xl font-bold text-gray-800">{{ $cliente->version_a3erp ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Grid principal: izquierda alertas+instancias, derecha licencias --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Columna Izquierda: Alertas + Instancias --}}
        <div class="lg:col-span-2 flex flex-col gap-8">

            {{-- SECCIÓN DE ALERTAS --}}
            @if($licenciasDesactualizadas->count() > 0)
            <div class="border-l-4 border-orange-500 bg-orange-50 p-6 rounded-r-xl shadow-sm">
                <div class="flex items-center mb-4">
                    <x-heroicon-s-exclamation-triangle class="w-6 h-6 text-orange-500 mr-2" />
                    <h3 class="text-lg font-bold text-orange-800">Actualizaciones de software pendientes</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($licenciasDesactualizadas as $lic)
                        <div class="bg-white p-4 rounded-lg border border-orange-200 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-gray-900 text-base">{{ $lic->desarrollo->nombre }}</p>
                                    <p class="text-sm text-gray-600 mt-2">
                                        Instalada:
                                        <span class="font-mono font-bold text-orange-600">
                                            @if($lic->version_instalada)
                                                v{{ $lic->version_instalada->version_major }}.{{ $lic->version_instalada->version_minor }}.{{ $lic->version_instalada->version_patch }}
                                            @else
                                                Desconocida
                                            @endif
                                        </span>
                                    </p>
                                    <p class="text-sm text-green-600 font-semibold">
                                        Disponible:
                                        <span class="font-mono">
                                            @if($lic->version_disponible)
                                                v{{ $lic->version_disponible->version_major }}.{{ $lic->version_disponible->version_minor }}.{{ $lic->version_disponible->version_patch }}
                                            @else
                                                Consultar catálogo
                                            @endif
                                        </span>
                                    </p>
                                </div>
                                <span class="text-[10px] bg-orange-100 text-orange-700 px-2 py-1 rounded uppercase font-black tracking-tighter">Actualizar</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Instancias --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <x-heroicon-o-computer-desktop class="w-5 h-5 mr-2 text-gray-500" />
                        Instancias (PCs)
                    </h3>
                    <span class="text-xs font-medium bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $cliente->instancias->count() }} equipos</span>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-6 py-3 font-bold">Nombre del Equipo</th>
                            <th class="px-6 py-3 font-bold">Última Actividad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($cliente->instancias as $instancia)
                        <tr class="hover:bg-gray-100 transition cursor-pointer"
                        onclick="window.location='{{ route('clientes.instancias.show', [$cliente->id, $instancia->id]) }}'">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $instancia->nombre }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $instancia->ultima_conexion ? \Carbon\Carbon::parse($instancia->ultima_conexion)->diffForHumans() : 'Sin conexión' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($erroresRecientes->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-100 bg-red-50 flex items-center">
                        <x-heroicon-o-exclamation-circle class="w-5 h-5 mr-2 text-red-500" />
                        <h3 class="font-bold text-red-800">Errores recientes (últimos 7 días)</h3>
                    </div>
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-bold">Fecha</th>
                                <th class="px-6 py-3 font-bold">Equipo</th>
                                <th class="px-6 py-3 font-bold">Desarrollo</th>
                                <th class="px-6 py-3 font-bold">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($erroresRecientes as $error)
                            <tr>
                                <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($error->fecha_actualizacion)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $error->instancia->nombre }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $error->version->desarrollo->nombre }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 italic">
                                    {{ $error->observaciones ?? 'Sin observaciones' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

        </div>

        {{-- Columna Derecha: Desarrollos Licenciados --}}
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <x-heroicon-o-key class="w-5 h-5 mr-2 text-gray-500" />
                        Desarrollos Licenciados
                    </h3>
                </div>

                {{-- Mensajes de éxito/error --}}
                @if(session('success'))
                    <div class="mx-4 mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mx-4 mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="p-4 space-y-3 flex-1">
                    @forelse($cliente->licencias as $licencia)
                        <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 bg-white shadow-sm hover:bg-gray-100 transition cursor-pointer"
                            onclick="window.location='{{ route('catalogo.show', $licencia->id_dev) }}'">
                            <span class="text-sm font-medium text-gray-700">{{ $licencia->desarrollo->nombre }}</span>
                            <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                                <form method="POST" action="{{ route('clientes.licencias.remove', [$cliente->id, $licencia->id_dev]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-400 hover:text-red-600 transition"
                                            onclick="return confirm('¿Eliminar esta licencia?')">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-400 italic">No hay licencias registradas</p>
                        </div>
                    @endforelse
                </div>

                {{-- Formulario para añadir licencia --}}
                @if($desarrollosDisponibles->count() > 0)
                    <div class="p-4 border-t border-gray-100">
                        <form method="POST" action="{{ route('clientes.licencias.add', $cliente->id) }}" class="flex gap-2">
                            @csrf
                            <select name="id_dev" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-600 focus:border-gray-600 outline-none appearance-none">                                <option value="">Seleccionar desarrollo...</option>
                                @foreach($desarrollosDisponibles as $dev)
                                    <option value="{{ $dev->id }}">{{ $dev->nombre }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-2 bg-gray-900 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition">
                                <x-heroicon-o-plus class="w-4 h-4" />
                                Añadir
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
