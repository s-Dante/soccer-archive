@extends('layouts.app') {{-- Usamos el layout principal de la app --}}

@section('title', 'Configuración de Perfil')

@section('content')
<div classclass="container mx-auto px-4 py-12 text-white">
    <h1 class="text-4xl font-bold mb-8 baloo-bhaijaan-2-bold">Configuración de Perfil</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- COLUMNA IZQUIERDA: Editar Información --}}
        <div class="lg:col-span-2">
            <div class="bg-gray-800/80 backdrop-blur-md p-6 sm:p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-6">Información del Perfil</h2>

                {{-- Mensaje de éxito para el perfil --}}
                @if (session('success'))
                    <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                {{-- Errores de validación generales del perfil --}}
                @if ($errors->any() && !$errors->hasAny(['profile_photo', 'current_password', 'password']))
                    <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-3 mb-6">
                        <p class="font-semibold">Hubo algunos problemas con tu envío:</p>
                        <ul class="list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                {{-- Evita mostrar errores de los otros formularios --}}
                                @if(!Str::contains($error, ['foto', 'contraseña']))
                                    <li>{{ $error }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form action="{{ route('user.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nombre --}}
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-300">Nombre</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                            @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        {{-- Apellido --}}
                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium text-gray-300">Apellido</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                            @error('last_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nombre de Usuario --}}
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-300">Nombre de Usuario</label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                            @error('username') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        {{-- Email --}}
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-300">Correo Electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                            @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Fecha de Nacimiento --}}
                        <div>
                            <label for="birthdate" class="block mb-2 text-sm font-medium text-gray-300">Fecha de Nacimiento</label>
                            <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate) }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                            @error('birthdate') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        {{-- Género --}}
                        <div>
                            <label for="gender" class="block mb-2 text-sm font-medium text-gray-300">Género</label>
                            <select id="gender" name="gender" class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Femenino</option>
                                <option value="prefer_not_to_say" {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefiero no decir</option>
                            </select>
                            @error('gender') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        {{-- País --}}
                        <div>
                            <label for="country" class="block mb-2 text-sm font-medium text-gray-300">País</label>
                            <select id="country" name="country" class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                                <option value="" disabled>Selecciona un país...</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('country', $user->country) == $country ? 'selected' : '' }}>
                                        {{ $country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-lg transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- COLUMNA DERECHA: Foto y Contraseña --}}
        <div class="lg:col-span-1 space-y-8">
            
            {{-- Tarjeta para Cambiar Foto --}}
            <div class="bg-gray-800/80 backdrop-blur-md p-6 sm:p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-6">Foto de Perfil</h2>

                {{-- Mensaje de éxito para la foto --}}
                @if (session('success_photo'))
                    <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-6">
                        {{ session('success_photo') }}
                    </div>
                @endif
                {{-- Errores de validación de la foto --}}
                @error('profile_photo')
                    <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-3 mb-6">
                        <p>{{ $message }}</p>
                    </div>
                @enderror

                <form action="{{ route('user.settings.photo') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="profile_photo" class="block mb-2 text-sm font-medium text-gray-300">Subir nueva foto</label>
                        <input type="file" id="profile_photo" name="profile_photo" 
                               class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-lg transition">
                        Actualizar Foto
                    </button>
                </form>
            </div>

            {{-- Tarjeta para Cambiar Contraseña --}}
            <div class="bg-gray-800/80 backdrop-blur-md p-6 sm:p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-6">Cambiar Contraseña</h2>

                {{-- Mensaje de éxito para la contraseña --}}
                @if (session('success_password'))
                    <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-6">
                        {{ session('success_password') }}
                    </div>
                @endif
                {{-- Errores de validación de la contraseña --}}
                @if ($errors->has('current_password') || $errors->has('password'))
                     <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-3 mb-6">
                        <p class="font-semibold">Error al cambiar contraseña:</p>
                        <ul class="list-disc list-inside mt-2">
                            @error('current_password') <li>{{ $message }}</li> @enderror
                            @error('password') <li>{{ $message }}</li> @enderror
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.settings.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="current_password" class="block mb-2 text-sm font-medium text-gray-300">Contraseña Actual</label>
                        <input type="password" id="current_password" name="current_password"
                               class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-300">Nueva Contraseña</label>
                        <input type="password" id="password" name="password"
                               class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-300">Confirmar Nueva Contraseña</label>

                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-lg transition">
                        Cambiar Contraseña
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection