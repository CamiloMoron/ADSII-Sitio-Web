<x-guest-layout>
    <div class="fade-in w-full max-w-md mx-4">
        <!-- Logo area -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style="background:#f59e0b;">
                <i data-lucide="shield-check" style="width:32px;height:32px;color:#0f172a;"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Sistema de Gestión</h1>
            <p class="text-sm mt-1" style="color:#64748b;">Ingrese sus credenciales</p>
        </div>

        <div class="rounded-2xl p-8" style="background:#1e293b;border:1px solid #334155;">
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-sm rounded-lg p-3" style="background:#065f4622;color:#34d399;border:1px solid #065f46;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Usuario -->
                <div>
                    <label for="usuario" class="block text-sm font-medium mb-2" style="color:#94a3b8;">Usuario</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2" style="width:18px;height:18px;color:#64748b;"></i>
                        <input id="usuario" 
                               type="text" 
                               name="usuario" 
                               value="{{ old('usuario') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               placeholder="Ingrese su usuario" 
                               class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-white outline-none focus:ring-2 transition"
                               style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                    </div>
                    @error('usuario')
                        <p class="mt-2 text-sm" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-2" style="color:#94a3b8;">Contraseña</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2" style="width:18px;height:18px;color:#64748b;"></i>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="Ingrese su contraseña" 
                               class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-white outline-none focus:ring-2 transition"
                               style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" 
                           type="checkbox" 
                           name="remember" 
                           class="rounded border-gray-600 text-amber-500 shadow-sm focus:ring-amber-500"
                           style="background:#0f172a;border-color:#334155;">
                    <label for="remember_me" class="ml-2 text-sm" style="color:#94a3b8;">
                        Recordarme
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-3 rounded-xl text-sm font-semibold transition-all hover:brightness-110 active:scale-[0.98]" 
                        style="background:#f59e0b;color:#0f172a;">
                    Iniciar Sesión
                </button>
            </form>

            <p class="text-xs text-center mt-5" style="color:#475569;">
                Usuarios demo: <span class="mono" style="color:#f59e0b;">admin</span>, 
                <span class="mono" style="color:#f59e0b;">asistente</span>, 
                <span class="mono" style="color:#f59e0b;">supervisor</span> · 
                Contraseña: <span class="mono" style="color:#f59e0b;">1234</span>
            </p>
        </div>
    </div>
</x-guest-layout>
