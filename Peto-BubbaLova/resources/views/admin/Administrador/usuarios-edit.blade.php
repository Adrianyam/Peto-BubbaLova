<x-admin-layout tittle="Editar Usuario">
    <div class="px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editar Usuario: {{ $user->name }}</h1>
            <a href="{{ route('admin.administrador.usuarios') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-4xl">
            <form action="{{ route('admin.administrador.usuarios.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                        <input type="text" name="last_name" id="last_name" required value="{{ old('last_name', $user->last_name) }}"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700 mb-1">Edad</label>
                        <input type="number" name="age" id="age" min="0" value="{{ old('age', $user->age) }}"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @error('age') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                           class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña (dejar en blanco para no cambiar)</label>
                        <input type="password" name="password" id="password"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                        <select name="role" id="role" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow transition">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>