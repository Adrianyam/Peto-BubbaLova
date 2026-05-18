<x-admin-layout tittle="Nuevo Material">
    <div class="px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Agregar al Inventario</h1>
            <a href="{{ route('admin.administrador.inventario') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
            <form action="{{ route('admin.administrador.inventario.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Material</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                           class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                           placeholder="Ej: Harina, Aceite, Servilletas">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Cantidad Inicial</label>
                    <input type="number" name="quantity" id="quantity" min="0" required value="{{ old('quantity', 0) }}"
                               class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                               placeholder="0">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow transition">
                        Registrar en Inventario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>