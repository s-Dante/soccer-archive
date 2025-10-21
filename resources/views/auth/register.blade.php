@extends('layouts.auth')

@section('title', 'Registro')

@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-6 drop-shadow">Sign In</h1>

    {{-- Mostramos errores de validación del backend --}}
    @if ($errors->any())
        <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-3 mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('auth.register') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        {{-- NOMBRES Y APELLIDOS --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="name" placeholder="Nombre(s)" value="{{ old('name') }}" required class="flex-1 w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="last_name" placeholder="Apellidos" value="{{ old('last_name') }}" required class="flex-1 w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- USERNAME Y CORREO --}}
        <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="email" name="email" placeholder="Correo" value="{{ old('email') }}" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">

        {{-- CONTRASEÑA Y VALIDACIÓN --}}
        <input type="password" id="password" name="password" placeholder="Contraseña" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <div id="password-feedback" class="text-xs space-y-1 text-neutral-400">
            <p id="min-length"> Mínimo 8 caracteres</p>
            <p id="mixed-case"> Mayúsculas y minúsculas</p>
            <p id="numbers"> Al menos un número</p>
            <p id="symbols"> Al menos un caracter especial</p>
        </div>
        <input type="password" name="password_confirmation" placeholder="Confirmar Contraseña" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        
        {{-- GÉNERO Y FECHA DE NACIMIENTO --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <select name="gender" required class="flex-1 w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Género</option>
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
                <option value="prefer_not_to_say">Prefiero no decir</option>
            </select>
            <input type="date" name="birthdate" title="Fecha de Nacimiento" value="{{ old('birthdate') }}" required class="flex-1 w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- PAÍS Y FOTO DE PERFIL --}}
        <input type="text" name="country" placeholder="País de Nacimiento" value="{{ old('country') }}" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <div>
            <label for="profile_photo" class="block text-sm font-medium text-neutral-300 mb-2">Foto de Perfil (Opcional)</label>
            <input type="file" name="profile_photo" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
        </div>

        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-3 transition">
            Registrarse
        </button>
    </form>

    <div class="mt-4 text-sm text-neutral-300 text-center">
        ¿Ya tienes una cuenta? <a href="{{ route('auth.login') }}" class="font-semibold hover:underline">Inicia Sesión</a>
    </div>

    {{-- El script para la validación de la contraseña --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.getElementById('password');
            const feedbackElements = {
                min: document.getElementById('min-length'),
                mixedCase: document.getElementById('mixed-case'),
                numbers: document.getElementById('numbers'),
                symbols: document.getElementById('symbols'),
            };

            // Función para actualizar la UI del feedback de la contraseña
            const updateFeedbackUI = (rule, isValid) => {
                const el = feedbackElements[rule];
                if (!el) return;

                const validIcon = '✓';
                const invalidIcon = '✗';
                
                el.classList.toggle('text-green-400', isValid);
                el.classList.toggle('text-neutral-400', !isValid);

                const text = el.innerText.replace(/[✓✗]/g, '').trim();
                el.innerHTML = `<span>${isValid ? validIcon : invalidIcon}</span> ${text}`;
            };

            // Evento para validar en tiempo real
            passwordInput.addEventListener('keyup', async (e) => {
                const password = e.target.value;
                try {
                    const response = await fetch("{{ route('api.validate-password') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ password: password })
                    });
                    const data = await response.json();
                    
                    updateFeedbackUI('min', data.rules.min);
                    updateFeedbackUI('mixedCase', data.rules.mixedCase);
                    updateFeedbackUI('numbers', data.rules.numbers);
                    updateFeedbackUI('symbols', data.rules.symbols);
                } catch (error) {
                    console.error('Error validating password:', error);
                }
            });
        });
    </script>
@endsection