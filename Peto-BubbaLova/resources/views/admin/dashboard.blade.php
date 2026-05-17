<x-admin-layout>
    <x-slot name="tittle">
        Dashboard
    </x-slot>

    <div class="px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Panel de Control</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Administrador -->
            <a href="{{ route('admin.administrador.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-xl font-bold tracking-tight text-gray-900">Admin</h5>
                    <i class="fa-solid fa-user-tie text-2xl text-gray-400"></i>
                </div>
                <p class="font-normal text-gray-500">Gestionar configuración.</p>
            </a>

            <!-- Cajeros -->
            <a href="{{ route('admin.cajeros.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-xl font-bold tracking-tight text-gray-900">Cajeros</h5>
                    <i class="fa-solid fa-cash-register text-2xl text-green-500"></i>
                </div>
                <p class="font-normal text-gray-500">Control de ventas.</p>
            </a>

            <!-- Cocineros -->
            <a href="{{ route('admin.cocineros.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-xl font-bold tracking-tight text-gray-900">Cocineros</h5>
                    <i class="fa-solid fa-utensils text-2xl text-gray-400"></i>
                </div>
                <p class="font-normal text-gray-500">Gestión de pedidos.</p>
            </a>

            <!-- Roles y Permisos -->
            <a href="{{ route('admin.roles.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-xl font-bold tracking-tight text-gray-900">Permisos</h5>
                    <i class="fa-solid fa-user-shield-halved text-2xl text-gray-400"></i>
                </div>
                <p class="font-normal text-gray-500">Ajustar accesos.</p>
            </a>
        </div>
    </div>
</x-admin-layout>