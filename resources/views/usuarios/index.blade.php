@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Usuarios y Perfiles</h2>
    <p class="text-sm mb-6" style="color:#64748b;">CUB03 — Administración de cuentas y roles</p>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 text-sm rounded-lg p-3 fade-in" style="background:#065f4622;color:#34d399;border:1px solid #065f46;">
            <span class="flex items-center gap-2">
                <i data-lucide="check-circle" style="width:16px;height:16px;"></i>
                {{ session('success') }}
            </span>
        </div>
    @endif

    <div class="rounded-xl overflow-hidden" style="background:#1e293b;border:1px solid #334155;">
        <div class="flex items-center justify-between p-4" style="border-bottom:1px solid #334155;">
            <span class="text-sm font-semibold text-white">{{ $usuarios->count() }} usuarios</span>
            <button onclick="openNewForm()" 
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:brightness-110" 
                    style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="user-plus" style="width:16px;height:16px;"></i>
                Nuevo Usuario
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#0f172a;">
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">ID</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Nombre</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Usuario</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Perfil</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Estado</th>
                        <th class="px-4 py-3 text-left font-medium" style="color:#64748b;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr class="transition hover:bg-white/5" style="border-top:1px solid #334155;">
                            <td class="px-4 py-3 text-white">{{ $usuario->id }}</td>
                            <td class="px-4 py-3 text-white font-medium">{{ $usuario->nombre }}</td>
                            <td class="px-4 py-3 mono" style="color:#f59e0b;">{{ $usuario->usuario }}</td>
                            <td class="px-4 py-3 text-white">{{ $usuario->role->display_name }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-0.5 rounded-md text-xs font-medium" 
                                      style="{{ $usuario->estado === 'Activo' ? 'background:#065f4622;color:#34d399;border:1px solid #065f46;' : 'background:#7f1d1d22;color:#fca5a5;border:1px solid #7f1d1d;' }}">
                                    {{ $usuario->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button onclick="openEditForm({{ $usuario->id }})" 
                                            class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110" 
                                            style="background:#3b82f622;color:#60a5fa;">
                                        Editar
                                    </button>
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Está seguro de eliminar este usuario?');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-1 rounded-md text-xs font-medium transition hover:brightness-110" 
                                                style="background:#7f1d1d44;color:#fca5a5;">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr style="border-top:1px solid #334155;">
                            <td colspan="6" class="px-4 py-8 text-center" style="color:#64748b;">
                                No hay usuarios registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Overlay for New Usuario -->
    <div id="new-form-overlay" class="hidden fixed inset-0 z-40 flex items-center justify-center" style="background:rgba(0,0,0,0.6);">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Nuevo Usuario</h3>
            <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Nombre Completo</label>
                    <input name="nombre" 
                           required 
                           value="{{ old('nombre') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Ej. María López">
                    @error('nombre')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Usuario</label>
                    <input name="usuario" 
                           required 
                           value="{{ old('usuario') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Ej. mlopez">
                    @error('usuario')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Contraseña</label>
                    <input name="password" 
                           type="password"
                           required 
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Mínimo 4 caracteres">
                    @error('password')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Iniciales (Opcional)</label>
                    <input name="initials" 
                           value="{{ old('initials') }}"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Ej. ML"
                           maxlength="10">
                    @error('initials')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Perfil</label>
                    <select name="role_id" 
                            required 
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="">Seleccione perfil</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Estado</label>
                    <select name="estado" 
                            required 
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-xs" style="color:#fca5a5;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" 
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110" 
                            style="background:#f59e0b;color:#0f172a;">
                        Guardar
                    </button>
                    <button type="button" 
                            onclick="closeNewForm()" 
                            class="flex-1 py-2.5 rounded-lg text-sm font-medium transition" 
                            style="background:#334155;color:#94a3b8;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Overlay for Edit Usuario -->
    <div id="edit-form-overlay" class="hidden fixed inset-0 z-40 flex items-center justify-center" style="background:rgba(0,0,0,0.6);">
        <div class="w-full max-w-lg mx-4 rounded-2xl p-6 fade-in" style="background:#1e293b;border:1px solid #334155;">
            <h3 class="text-lg font-bold text-white mb-4">Editar Usuario</h3>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Nombre Completo</label>
                    <input name="nombre" 
                           id="edit-nombre"
                           required 
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Usuario</label>
                    <input name="usuario" 
                           id="edit-usuario"
                           required 
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Contraseña (dejar vacío para no cambiar)</label>
                    <input name="password" 
                           id="edit-password"
                           type="password"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;" 
                           placeholder="Nueva contraseña (opcional)">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Iniciales (Opcional)</label>
                    <input name="initials" 
                           id="edit-initials"
                           class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2 mono" 
                           style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;"
                           maxlength="10">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Perfil</label>
                    <select name="role_id" 
                            id="edit-role_id"
                            required 
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#94a3b8;">Estado</label>
                    <select name="estado" 
                            id="edit-estado"
                            required 
                            class="w-full px-3 py-2.5 rounded-lg text-sm text-white outline-none focus:ring-2" 
                            style="background:#0f172a;border:1px solid #334155;--tw-ring-color:#f59e0b;">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" 
                            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110" 
                            style="background:#f59e0b;color:#0f172a;">
                        Actualizar
                    </button>
                    <button type="button" 
                            onclick="closeEditForm()" 
                            class="flex-1 py-2.5 rounded-lg text-sm font-medium transition" 
                            style="background:#334155;color:#94a3b8;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Usuario data for edit modal
    const usuarios = @json($usuarios);

    function openNewForm() {
        document.getElementById('new-form-overlay').classList.remove('hidden');
    }

    function closeNewForm() {
        document.getElementById('new-form-overlay').classList.add('hidden');
    }

    function openEditForm(id) {
        const usuario = usuarios.find(u => u.id === id);
        if (!usuario) return;

        document.getElementById('edit-nombre').value = usuario.nombre;
        document.getElementById('edit-usuario').value = usuario.usuario;
        document.getElementById('edit-password').value = '';
        document.getElementById('edit-initials').value = usuario.initials || '';
        document.getElementById('edit-role_id').value = usuario.role_id;
        document.getElementById('edit-estado').value = usuario.estado;
        
        document.getElementById('edit-form').action = `/usuarios/${id}`;
        document.getElementById('edit-form-overlay').classList.remove('hidden');
    }

    function closeEditForm() {
        document.getElementById('edit-form-overlay').classList.add('hidden');
    }

    @if($errors->any() && old('_method') === null)
        openNewForm();
    @endif

    lucide.createIcons();
</script>
@endsection
