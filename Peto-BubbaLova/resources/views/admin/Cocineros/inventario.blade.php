<x-admin-layout tittle="Inventario - Cocina">
    <div class="px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Inventario (Vista Cocina)</h1>
            <p class="text-gray-500"><i class="fa-solid fa-eye mr-2"></i> Solo lectura</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Material</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad Disponible</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($materials as $material)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $material->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $material->quantity }} unidades
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($material->quantity < 20)
                                    <span class="px-2 py-1 rounded bg-red-100 text-red-800 font-bold animate-pulse text-xs">
                                        REABASTECER URGENTE
                                    </span>
                                @elseif($material->quantity <= 30)
                                    <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800 font-bold text-xs">
                                        STOCK BAJO
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded bg-green-100 text-green-800 font-bold text-xs">
                                        OK
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400">
                                No hay materiales registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>