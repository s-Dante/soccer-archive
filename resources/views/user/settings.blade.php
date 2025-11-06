@extends('layouts.app') {{-- Asumiendo que tu layout principal se llama 'app' --}}

@section('content')
<div classclass="w-full max-w-4xl mx-auto py-12 px-4">
    <h1 class="text-4xl font-bold mb-8 text-white">Ajustes del Perfil</h1>

    {{-- Contenedor principal del formulario --}}
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        
        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensajes de error de validación --}}
        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-3 mb-6">
                <p class="font-semibold mb-2">Por favor, corrige los siguientes errores:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        {{-- INICIO DEL FORMULARIO --}}
        <form action="{{ route('user.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH') {{-- Usamos PATCH para actualizar --}}

            {{-- Fila para Nombre y Apellido --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-300">Nombre</label>
                    <input type="text" id="name" name="name" 
                           value="{{ old('name', $user->name) }}" 
                           class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-300">Apellido</label>
                    <input type="text" id="last_name" name="last_name" 
                           value="{{ old('last_name', $user->last_name) }}" 
                           class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
                </div>
            </div>

            {{-- Fila para Email y Username --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-300">Correo Electrónico</label>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email', $user->email) }}" 
                           class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
                </div>
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-300">Nombre de Usuario</label>
                    <input type="text" id="username" name="username" 
                           value="{{ old('username', $user->username) }}" 
                           class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
                </div>
            </div>

            {{-- Fila para Fecha de Nacimiento y Género --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="birthdate" class="block mb-2 text-sm font-medium text-gray-300">Fecha de Nacimiento</label>
                    <input type="date" id="birthdate" name="birthdate" 
                           value="{{ old('birthdate', $user->birthdate) }}" 
                           class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
                </div>
                <div>
                    <label for="gender" class="block mb-2 text-sm font-medium text-gray-300">Género</Glabel>
                    <select id="gender" name="gender" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Femenino</option>
                        <option value="prefer_not_to_say" {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefiero no decirlo</option>
                    </select>
                </div>
            </div>
            
            {{-- Fila para País --}}
            <div>
                <label for="country" class="block mb-2 text-sm font-medium text-gray-300">País</label>
                <select id="country" name="country" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
                    <option value="" disabled>Selecciona un país...</option>
                    @isset($countries)
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ old('country', $user->country) == $country ? 'selected' : '' }}>
                                {{ $country }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>Error: No se pudo cargar la lista de países.</option>
                    @endisset
                </select>
            </div>

            {{-- Botón de Guardar --}}
            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-lg transition">
                    Guardar Cambios
                </button>
            </div>

        </form>
        {{-- FIN DEL FORMULARIO --}}
    </div>
</div>
@endsection