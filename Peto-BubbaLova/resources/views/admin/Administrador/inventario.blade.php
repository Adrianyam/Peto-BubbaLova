<x-admin-layout tittle="Inventario">
    <div class="px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Inventario de Materiales</h1>
            <a href="{{ route('admin.administrador.inventario.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow transition flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Agregar al inventario
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($materials->isEmpty())
                <div class="p-10 text-center">
                    <i class="fa-solid fa-boxes-stacked text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">No hay materiales en el inventario.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($materials as $material)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    #{{ $material->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $material->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @php
                                        $colorClass = 'bg-green-100 text-green-800';
                                        if ($material->quantity < 20) {
                                            $colorClass = 'bg-red-100 text-red-800 animate-pulse';
                                        } elseif ($material->quantity <= 30) {
                                            $colorClass = 'bg-yellow-100 text-yellow-800';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 rounded {{ $colorClass }} font-semibold">
                                        {{ $material->quantity }} unidades
                                    </span>
                                    
                                    <button onclick="openAddStockModal({{ $material->id }}, '{{ $material->name }}')" class="ml-2 text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-1 rounded-full px-2 text-xs font-bold transition-colors">
                                        <i class="fa-solid fa-plus mr-1"></i> Stock
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.administrador.inventario.destroy', $material) }}" method="POST" class="inline" data-swal-confirm="true">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    @push('js')
    <script>
        async function openAddStockModal(id, name) {
            const { value: amount } = await Swal.fire({
                title: 'Agregar Stock',
                text: `¿Cuántas unidades deseas añadir a "${name}"?`,
                input: 'number',
                inputAttributes: {
                    min: 1,
                    step: 1
                },
                inputValue: 1,
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#4f46e5',
                inputValidator: (value) => {
                    if (!value || value < 1) {
                        return '¡Debes ingresar una cantidad válida!'
                    }
                }
            });

            if (amount) {
                // Generar la URL de la ruta usando Blade para asegurar que es correcta
                const actionUrl = "{{ route('admin.administrador.inventario.add-stock', ['material' => ':id']) }}".replace(':id', id);
                
                // Crear un formulario dinámico para enviar el PATCH
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                
                const amountInput = document.createElement('input');
                amountInput.type = 'hidden';
                amountInput.name = 'amount';
                amountInput.value = amount;
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                form.appendChild(amountInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    @endpush
</x-admin-layout>