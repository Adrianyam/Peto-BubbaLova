<x-admin-layout tittle="Productos">
    <div class="px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Listado de Productos</h1>
            <a href="{{ route('admin.administrador.productos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow transition flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Nuevo Producto
            </a>
        </div>

        @if($products->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
                <i class="fa-solid fa-box-open text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg">Aún no hay productos registrados.</p>
                <a href="{{ route('admin.administrador.productos.create') }}" class="text-indigo-600 hover:underline mt-2 inline-block">Crea tu primer producto aquí</a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="h-32 w-full bg-gray-100 flex items-center justify-center overflow-hidden">
                            @if($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</h3>
                            <p class="text-indigo-600 font-semibold text-lg mt-1">${{ number_format($product->price, 2) }}</p>
                            <p class="text-gray-600 text-xs mt-1 line-clamp-2 h-7">{{ $product->description }}</p>
                            
                            <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end space-x-2">
                                <a href="{{ route('admin.administrador.productos.edit', $product) }}" class="text-gray-400 hover:text-indigo-600 transition-colors text-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.administrador.productos.destroy', $product) }}" method="POST" class="inline" data-swal-confirm="true">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors text-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>