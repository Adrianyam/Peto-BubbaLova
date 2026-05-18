<x-admin-layout tittle="Pedidos del Día">
    <div class="px-4 py-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pedidos del Día</h1>
                <p class="text-gray-500 capitalize"><i class="fa-solid fa-calendar-day mr-2 text-indigo-500"></i>{{ \Carbon\Carbon::today()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('admin.administrador.pedidos.pdf') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fa-solid fa-file-pdf mr-2"></i> Generar PDF
                </a>
                
                <form action="{{ route('admin.administrador.pedidos.email') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Enviar por Correo
                    </button>
                </form>
            </div>
        </div>

        <!-- Estadísticas de Cajeros -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach($cashierStats as $cashier)
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 p-3 rounded-full">
                            <i class="fa-solid fa-user-tie text-indigo-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 truncate">Cajero: {{ $cashier->name }}</p>
                            <p class="text-lg font-bold text-gray-900">{{ $cashier->orders_count }} pedidos hoy</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Listado Detallado de Pedidos -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h2 class="font-bold text-gray-700 uppercase tracking-wider text-sm flex items-center">
                    <i class="fa-solid fa-list-check mr-2"></i> Registro de Actividad
                </h2>
            </div>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cajero</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gasto Insumos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('H:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-indigo-600">{{ $order->user->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $order->customer_name ?? 'Público General' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <ul class="list-disc list-inside">
                                    @foreach($order->items as $item)
                                        <li>{{ $item->quantity }}x {{ $item->product->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($order->materials->count() > 0)
                                    <ul class="space-y-1">
                                        @foreach($order->materials as $mat)
                                            <li class="flex items-center text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">
                                                <i class="fa-solid fa-flask mr-1 text-[10px]"></i>
                                                {{ $mat->pivot->quantity }}x {{ $mat->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-300 italic text-[10px]">No registrado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900">
                                ${{ number_format($order->total, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                No se han registrado pedidos el día de hoy.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>