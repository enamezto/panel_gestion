@extends('layouts.app')

@section('title', 'Token de activación')
@section('page_title', 'Token de activación generado')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Cabecera de éxito --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                <x-heroicon-o-check-circle class="w-6 h-6 text-green-600" />
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Cliente creado correctamente</h2>
                <p class="text-sm text-gray-500">{{ $cliente->nombre }} · {{ $cliente->codigo }}</p>
            </div>
        </div>

        {{-- Aviso crítico --}}
        <div class="p-4 bg-yellow-50 border-2 border-yellow-300 rounded-lg mb-6">
            <div class="flex items-start gap-3">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" />
                <div>
                    <p class="text-sm font-bold text-yellow-700">⚠️ Copia este token ahora</p>
                    <p class="text-xs text-yellow-600 mt-1">
                        Este token <strong>solo se muestra una vez</strong> y no podrá recuperarse.
                        Si lo pierdes, tendrás que generar uno nuevo desde la base de datos.
                        Entrégaselo al cliente junto con el instalador.
                    </p>
                </div>
            </div>
        </div>

        {{-- Token --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Token de activación del cliente
            </label>
            <div class="flex items-center gap-2">
                <input type="text"
                       id="token-field"
                       value="{{ $token }}"
                       readonly
                       class="flex-1 px-4 py-3 bg-gray-900 text-white font-mono text-sm rounded-lg border border-gray-700 focus:outline-none">
                <button onclick="copiarToken()"
                        id="btn-copiar"
                        class="inline-flex items-center gap-2 px-4 py-3 bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition flex-shrink-0">
                    <x-heroicon-o-clipboard class="w-4 h-4" />
                    Copiar
                </button>
            </div>
        </div>

        {{-- Instrucciones de uso --}}
        <div class="p-4 bg-gray-100 border border-gray-300 rounded-lg">
            <p class="text-sm font-medium text-black mb-2">¿Cómo usar este token?</p>
            <ol class="text-xs text-black space-y-1 list-decimal list-inside">
                <li>Entrega este token al técnico que va a instalar el software en el cliente.</li>
                <li>El instalador usará este token en la cabecera <code class="bg-gray-300 px-1 rounded">Authorization: Bearer &lt;token&gt;</code> para identificarse.</li>
                <li>Al registrar la primera instancia, el sistema generará automáticamente un token de instancia.</li>
                <li>A partir de ese momento, el token de cliente ya no será necesario.</li>
            </ol>
        </div>

    </div>

    {{-- Botones de acción --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('clientes.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition">
            <x-heroicon-o-arrow-left class="w-4 h-4" />
            Volver al listado
        </a>
        <a href="{{ route('clientes.show', $cliente->id) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 rounded-lg transition">
            Ver ficha del cliente
            <x-heroicon-o-arrow-right class="w-4 h-4" />
        </a>
    </div>

</div>

{{-- Script para copiar el token --}}
<script>
    function copiarToken() {
        const tokenField = document.getElementById('token-field');
        const btnCopiar = document.getElementById('btn-copiar');

        navigator.clipboard.writeText(tokenField.value).then(() => {
            btnCopiar.innerHTML = `
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                ¡Copiado!
            `;
            btnCopiar.classList.remove('bg-gray-800', 'hover:bg-gray-700');
            btnCopiar.classList.add('bg-green-600', 'hover:bg-green-700');

            setTimeout(() => {
                btnCopiar.innerHTML = `
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                    </svg>
                    Copiar
                `;
                btnCopiar.classList.remove('bg-green-600', 'hover:bg-green-700');
                btnCopiar.classList.add('bg-gray-800', 'hover:bg-gray-700');
            }, 3000);
        });
    }
</script>
@endsection
