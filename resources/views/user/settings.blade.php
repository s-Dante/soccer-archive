<x-app-layout>
    <div class="container mx-auto px-4 py-8 text-white">
        <h1 class="text-4xl font-bold mb-8">Configuración de la Cuenta</h1>

        <div class="max-w-2xl mx-auto bg-gray-800 p-8 rounded-lg shadow-lg">
            
            {{-- FORMULARIO DE DATOS PERSONALES --}}
            <h2 class="text-2xl font-semibold mb-6">Información Personal</h2>
            <form action="{{-- route('user.settings.update') --}}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH') {{-- Usamos PATCH para actualizar --}}

                {{-- Nombre y Apellido --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-300">Nombre(s)</label>
                        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div>
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-300">Apellidos</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                    </div>
                </div>

                {{-- Correo (usualmente no se puede cambiar) --}}
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-300">Correo Electrónico</label>
                    <input type="email" id="email" value="{{ Auth::user()->email }}" class="bg-gray-900 border border-gray-700 text-gray-400 text-sm rounded-lg block w-full p-2.5" disabled>
                </div>
                
                {{-- Foto de Perfil --}}
                <div>
                    <label for="profile_photo" class="block mb-2 text-sm font-medium text-gray-300">Foto de Perfil</label>
                    <input type="file" id="profile_photo" name="profile_photo" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg transition">Guardar Cambios</button>
            </form>

            {{-- FORMULARIO PARA CAMBIAR CONTRASEÑA --}}
            <div class="border-t border-gray-700 mt-10 pt-8">
                <h2 class="text-2xl font-semibold mb-6">Cambiar Contraseña</h2>
                <form action="{{-- route('user.password.update') --}}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT') {{-- Usamos PUT para la contraseña --}}
                    
                    <div>
                        <label for="current_password" class="block mb-2 text-sm font-medium text-gray-300">Contraseña Actual</label>
                        <input type="password" id="current_password" name="current_password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-300">Nueva Contraseña</label>
                        <input type="password" id="password" name="password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-300">Confirmar Nueva Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition">Actualizar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>