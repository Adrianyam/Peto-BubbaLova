<x-admin-layout tittle="Pedidos en Cocina">
    <div class="px-4 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Panel de Cocina</h1>
            <p class="text-gray-500">Gestión de pedidos por estado</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- COLUMNA: EN ESPERA -->
            <div class="bg-gray-100 p-4 rounded-xl shadow-inner border border-gray-200">
                <h2 class="text-lg font-bold text-orange-600 mb-4 flex items-center justify-between">
                    <span><i class="fa-solid fa-clock mr-2"></i> EN ESPERA</span>
                    <span class="bg-orange-200 text-orange-700 text-xs px-2 py-1 rounded-full">{{ $orders->where('status', 'pendiente')->count() }}</span>
                </h2>
                <div class="space-y-4">
                    @foreach($orders->where('status', 'pendiente') as $order)
                        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-orange-500 transition hover:shadow-md">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-black text-gray-800">#{{ $order->id }}</span>
                                <span class="text-xs font-medium text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-xs text-indigo-500 font-semibold mb-3">{{ $order->customer_name ?? 'Cliente General' }}</p>
                            
                            <div class="space-y-1 mb-4">
                                @foreach($order->items as $item)
                                    <div class="text-sm text-gray-700 flex justify-between">
                                        <span class="font-bold">{{ $item->quantity }}x</span>
                                        <span class="flex-1 ml-2">{{ $item->product->name }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <form action="{{ route('admin.cocineros.pedidos.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="en_preparacion">
                                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold py-2 rounded shadow-sm transition">
                                    PREPARAR AHORA
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- COLUMNA: EN PREPARACIÓN -->
            <div class="bg-blue-50 p-4 rounded-xl shadow-inner border border-blue-100">
                <h2 class="text-lg font-bold text-blue-600 mb-4 flex items-center justify-between">
                    <span><i class="fa-solid fa-fire-burner mr-2"></i> EN PREPARACIÓN</span>
                    <span class="bg-blue-200 text-blue-700 text-xs px-2 py-1 rounded-full">{{ $orders->where('status', 'en_preparacion')->count() }}</span>
                </h2>
                <div class="space-y-4">
                    @foreach($orders->where('status', 'en_preparacion') as $order)
                        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500 transition hover:shadow-md animate-pulse">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-black text-gray-800">#{{ $order->id }}</span>
                                <span class="text-xs font-medium text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-xs text-indigo-500 font-semibold mb-3">{{ $order->customer_name ?? 'Cliente General' }}</p>
                            
                            <div class="space-y-1 mb-4">
                                @foreach($order->items as $item)
                                    <div class="text-sm text-gray-700 flex justify-between">
                                        <span class="font-bold">{{ $item->quantity }}x</span>
                                        <span class="flex-1 ml-2">{{ $item->product->name }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <form action="{{ route('admin.cocineros.pedidos.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="listo">
                                
                                <div class="mb-4 bg-white p-3 rounded-lg border border-blue-200">
                                    <p class="text-[11px] font-bold text-blue-800 mb-2 uppercase leading-none"><i class="fa-solid fa-box-open mr-1"></i> Insumos usados:</p>
                                    <div class="space-y-2">
                                        @foreach($materials as $material)
                                            <div class="flex items-center justify-between gap-3 py-1 border-b border-blue-50 last:border-0">
                                                <label class="text-[11px] text-gray-700 font-semibold truncate flex-1">{{ $material->name }}</label>
                                                <div class="flex items-center">
                                                    <input type="number" 
                                                        name="materials[{{ $material->id }}]" 
                                                        value="0" 
                                                        min="0" 
                                                        max="{{ $material->quantity }}"
                                                        class="w-14 h-7 text-xs font-bold text-center border-2 border-blue-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-blue-50 text-blue-900">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-[9px] text-blue-500 mt-2 font-medium italic">* El stock se descontará al completar</p>
                                </div>

                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 rounded shadow-sm transition uppercase tracking-wider">
                                    TERMINAR Y DESCONTAR
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- COLUMNA: COMPLETADA (Últimos 10) -->
            <div class="bg-green-50 p-4 rounded-xl shadow-inner border border-green-100">
                <h2 class="text-lg font-bold text-green-600 mb-4 flex items-center justify-between">
                    <span><i class="fa-solid fa-circle-check mr-2"></i> COMPLETADA</span>
                    <span class="bg-green-200 text-green-700 text-xs px-2 py-1 rounded-full">{{ $orders->where('status', 'listo')->count() }}</span>
                </h2>
                <div class="space-y-4 opacity-75">
                    @foreach($orders->where('status', 'listo') as $order)
                        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500 grayscale-[0.5]">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-black text-gray-800">#{{ $order->id }}</span>
                                <span class="text-xs font-medium text-gray-400">{{ $order->updated_at->format('H:i') }}</span>
                            </div>
                            <p class="text-xs text-gray-500 font-semibold mb-1 italic">Entregado</p>
                            
                            <div class="space-y-1">
                                @foreach($order->items as $item)
                                    <div class="text-xs text-gray-400 flex justify-between line-through">
                                        <span>{{ $item->quantity }}x</span>
                                        <span>{{ $item->product->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>