<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::with('role')->orderBy('id', 'asc')->get();
        $roles = Role::all();
        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'usuario' => ['required', 'string', 'unique:users,usuario'],
            'password' => ['required', 'string', 'min:4'],
            'initials' => ['nullable', 'string', 'max:10'],
            'role_id' => ['required', 'exists:roles,id'],
            'estado' => ['required', 'in:Activo,Inactivo'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'usuario.required' => 'El usuario es obligatorio.',
            'usuario.unique' => 'Este usuario ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'role_id.required' => 'El perfil es obligatorio.',
            'role_id.exists' => 'El perfil seleccionado no es válido.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario registrado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'usuario' => ['required', 'string', Rule::unique('users')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:4'],
            'initials' => ['nullable', 'string', 'max:10'],
            'role_id' => ['required', 'exists:roles,id'],
            'estado' => ['required', 'in:Activo,Inactivo'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'usuario.required' => 'El usuario es obligatorio.',
            'usuario.unique' => 'Este usuario ya está registrado.',
            'role_id.required' => 'El perfil es obligatorio.',
            'role_id.exists' => 'El perfil seleccionado no es válido.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $usuario->update($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
