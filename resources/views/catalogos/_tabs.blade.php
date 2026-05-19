<div class="flex space-x-4 border-b mb-6" style="border-bottom-color:#334155;">
    <a href="{{ route('clientes.index') }}" 
       class="pb-3 transition {{ request()->routeIs('clientes.*') ? 'border-b-2 text-white' : 'text-gray-400 hover:text-white' }}"
       style="{{ request()->routeIs('clientes.*') ? 'border-bottom-color:#f59e0b;color:#f59e0b;' : '' }}">
        Clientes
    </a>
    <a href="{{ route('vehiculos.index') }}" 
       class="pb-3 transition {{ request()->routeIs('vehiculos.*') ? 'border-b-2 text-white' : 'text-gray-400 hover:text-white' }}"
       style="{{ request()->routeIs('vehiculos.*') ? 'border-bottom-color:#f59e0b;color:#f59e0b;' : '' }}">
        Vehículos
    </a>
    <a href="{{ route('materiales.index') }}" 
       class="pb-3 transition {{ request()->routeIs('materiales.*') ? 'border-b-2 text-white' : 'text-gray-400 hover:text-white' }}"
       style="{{ request()->routeIs('materiales.*') ? 'border-bottom-color:#f59e0b;color:#f59e0b;' : '' }}">
        Materiales
    </a>
</div>
