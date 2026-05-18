<x-admin-layout tittle="Nuevo Producto">
    <div class="px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Agregar Nuevo Producto</h1>
            <a href="{{ route('admin.administrador.productos') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
            <form action="{{ route('admin.administrador.productos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                           class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                           placeholder="Ej: Hamburguesa Especial">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="price" id="price" step="0.01" min="0" required value="{{ old('price') }}"
                               class="block w-full rounded-md border-gray-300 pl-7 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="0.00">
                    </div>
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                              placeholder="Describe brevemente el producto...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen del Producto</label>
                    <div id="image-preview-container" class="mb-4 hidden text-center">
                        <img id="image-preview" src="" alt="Vista previa" class="mx-auto h-48 w-48 object-cover rounded-lg border-2 border-indigo-500 shadow-md">
                    </div>
                    <div id="upload-placeholder" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <i class="fa-solid fa-image text-gray-400 text-3xl mb-2"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Subir un archivo</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                </label>
                                <p class="pl-1">o arrastrar y soltar</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                        </div>
                    </div>
                    @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow transition">
                        Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('image-preview-container');
            const placeholder = document.getElementById('upload-placeholder');
            
            reader.onload = function() {
                preview.src = reader.result;
                container.classList.remove('hidden');
                placeholder.classList.add('border-indigo-300', 'bg-indigo-50');
            }
            
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</x-admin-layout>