<x-admin-layout tittle="Ventas - Cajeros">
    <div class="px-4 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sección de Productos -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fa-solid fa-utensils mr-2 text-indigo-600"></i> Menú de Productos
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($products as $product)
                            <div class="border rounded-lg p-3 hover:border-indigo-400 cursor-pointer transition-all group flex flex-col h-full"
                                 onclick="addToOrder({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                                <div class="bg-gray-100 h-32 rounded-md mb-2 overflow-hidden flex items-center justify-center">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    @else
                                        <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                                    @endif
                                </div>
                                <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors uppercase text-sm truncate">{{ $product->name }}</h3>
                                <p class="text-indigo-600 font-bold mt-auto text-lg">${{ number_format($product->price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sección del Pedido (Carrito) -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 sticky top-20 overflow-hidden">
                    <div class="bg-indigo-600 p-4">
                        <h2 class="text-white font-bold flex items-center">
                            <i class="fa-solid fa-receipt mr-2"></i> Pedido Actual
                        </h2>
                    </div>

                    <form action="{{ route('admin.cajeros.pedidos.store') }}" method="POST" id="order-form" class="p-4">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Cliente (Opcional)</label>
                            <input type="text" name="customer_name" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Ej: Juan Pérez">
                        </div>

                        <div id="order-items" class="space-y-3 mb-6 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar border-b pb-4">
                            <!-- Aquí se inyectarán los items -->
                        </div>

                        <div id="empty-cart" class="text-center py-10 text-gray-400 italic">
                            <i class="fa-solid fa-shopping-cart text-4xl mb-2 block opacity-20"></i>
                            Selecciona productos para comenzar
                        </div>

                        <div class="border-t pt-4 bg-gray-50 -mx-4 px-4 pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-gray-600 font-medium">TOTAL A PAGAR:</span>
                                <span class="text-2xl font-black text-indigo-700" id="grand-total">$0.00</span>
                            </div>

                            <button type="submit" id="submit-btn" disabled
                                    class="w-full bg-gray-400 text-white font-bold py-3 rounded-lg shadow-md transition-all cursor-not-allowed uppercase tracking-wider">
                                Confirmar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        let order = [];

        function addToOrder(id, name, price) {
            const existingItem = order.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                order.push({ id, name, price, quantity: 1 });
            }
            
            renderOrder();
        }

        function removeFromOrder(id) {
            order = order.filter(item => item.id !== id);
            renderOrder();
        }

        function updateQuantity(id, delta) {
            const item = order.find(item => item.id === id);
            if (item) {
                item.quantity += delta;
                if (item.quantity <= 0) {
                    removeFromOrder(id);
                } else {
                    renderOrder();
                }
            }
        }

        function renderOrder() {
            const itemsContainer = document.getElementById('order-items');
            const submitBtn = document.getElementById('submit-btn');
            const grandTotalEl = document.getElementById('grand-total');
            const emptyCart = document.getElementById('empty-cart');
            
            itemsContainer.innerHTML = '';
            let total = 0;

            if (order.length === 0) {
                emptyCart.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                grandTotalEl.innerText = `$0.00`;
            } else {
                emptyCart.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');

                order.forEach((item, index) => {
                    const subtotal = item.price * item.quantity;
                    total += subtotal;

                    const itemHtml = `
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100 group mb-2">
                            <div class="flex-1">
                                <h4 class="text-xs font-bold text-gray-800 uppercase">${item.name}</h4>
                                <span class="text-gray-500 text-xs">$${item.price.toFixed(2)} x ${item.quantity} = $${subtotal.toFixed(2)}</span>
                                <input type="hidden" name="items[${index}][id]" value="${item.id}">
                                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center border rounded-lg bg-white overflow-hidden h-8">
                                    <button type="button" onclick="updateQuantity(${item.id}, -1)" class="px-2 hover:bg-gray-100 text-gray-600 transition-colors">-</button>
                                    <span class="px-3 text-sm font-bold text-gray-700">${item.quantity}</span>
                                    <button type="button" onclick="updateQuantity(${item.id}, 1)" class="px-2 hover:bg-gray-100 text-gray-600 transition-colors">+</button>
                                </div>
                                <button type="button" onclick="removeFromOrder(${item.id})" class="text-gray-400 hover:text-red-600 p-1">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
                });
                grandTotalEl.innerText = `$${total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            }
        }

        // Estilos para el scrollbar custom
        const style = document.createElement('style');
        style.textContent = `
            .custom-scrollbar::-webkit-scrollbar { width: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        `;
        document.head.append(style);
    </script>
    @endpush
</x-admin-layout>