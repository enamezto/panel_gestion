@extends('layouts.app')

@section('title', 'Nuevo Cliente')
@section('page_title', 'Crear nuevo cliente')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Botón Volver --}}
    <div class="mb-6">
        <a href="{{ route('clientes.index') }}"
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Volver al listado
        </a>
    </div>

    {{-- Formulario --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">

        <h2 class="text-xl font-bold text-gray-800 mb-1">Datos del cliente</h2>
        <p class="text-sm text-gray-500 mb-6">
            Al crear el cliente, el sistema generará automáticamente un token de activación
            que deberás entregar al cliente para configurar el instalador.
        </p>

        {{-- Errores --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <x-heroicon-o-exclamation-circle class="w-4 h-4 text-red-500" />
                    <p class="text-sm font-medium text-red-700">Corrige los siguientes errores:</p>
                </div>
                <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf

            {{-- Nombre --}}
            <div class="mb-5">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre de la empresa <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       value="{{ old('nombre') }}"
                       placeholder="Ej: Bodegas Riojanas SA"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500 outline-none transition {{ $errors->has('nombre') ? 'border-red-300' : '' }}">
            </div>

            {{-- Código --}}
            <div class="mb-5">
                <label for="codigo" class="block text-sm font-medium text-gray-700 mb-1">
                    Código interno <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="codigo"
                       name="codigo"
                       value="{{ old('codigo') }}"
                       placeholder="Ej: CLI-006"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-gray-500 focus:border-gray-500 outline-none transition {{ $errors->has('codigo') ? 'border-red-300' : '' }}">
                <p class="text-xs text-gray-400 mt-1">Se guardará en mayúsculas automáticamente.</p>
            </div>

            {{-- Versión a3ERP --}}
            <div class="mb-8">
                <label for="version_a3erp" class="block text-sm font-medium text-gray-700 mb-1">
                    Versión de a3ERP instalada
                </label>
                <input type="text"
                       id="version_a3erp"
                       name="version_a3erp"
                       value="{{ old('version_a3erp') }}"
                       placeholder="Ej: 12.5.0"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-gray-500 focus:border-gray-500 outline-none transition">
                <p class="text-xs text-gray-400 mt-1">Opcional. Se puede actualizar más adelante.</p>
            </div>

            {{-- Aviso importante --}}
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-3">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" />
                <div>
                    <p class="text-sm font-medium text-amber-800">Token de activación</p>
                    <p class="text-xs text-amber-700 mt-0.5">
                        Al crear el cliente se generará un token único. Este token
                        <strong>solo se mostrará una vez</strong> y deberás copiarlo
                        para entregárselo al cliente junto con el instalador.
                    </p>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('clientes.index') }}"
                   class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Crear cliente y generar token
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
