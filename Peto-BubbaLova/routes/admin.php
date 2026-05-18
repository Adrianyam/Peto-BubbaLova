<?php 

use Illuminate\Support\Facades\Route; 

Route::middleware(['auth'])->group(function () {

    Route::get('/', function(){ 
        return view('admin.dashboard'); 
    })->name('dashboard');

    // RUTAS SOLO PARA ADMINISTRADOR
    Route::middleware(['role:Administrador'])->group(function () {
        Route::get('/roles', function(){ 
            return view('admin.roles.index'); 
        })->name('roles.index');

        Route::get('/roles/create', function(){ 
            return view('admin.roles.create'); 
        })->name('roles.create');

        Route::post('/roles', function(\Illuminate\Http\Request $request){
            $request->validate([
                'name' => 'required|unique:roles,name'
            ]);

            \Spatie\Permission\Models\Role::create([
                'name' => $request->name
            ]);

            return redirect()->route('admin.roles.index')->with('swal', [
                'icon' => 'success',
                'title' => '¡Hecho!',
                'text' => 'El rol se ha creado correctamente.'
            ]);
        })->name('roles.store');

        Route::get('/roles/{role}/edit', function($role){ 
            return view('admin.roles.edit', compact('role')); 
        })->name('roles.edit');

        Route::put('/roles/{role}', function(\Illuminate\Http\Request $request, $roleId){
            $request->validate([
                'name' => 'required|unique:roles,name,' . $roleId
            ]);

            $role = \Spatie\Permission\Models\Role::findOrFail($roleId);
            $role->update([
                'name' => $request->name
            ]);

            return redirect()->route('admin.roles.index')->with('swal', [
                'icon' => 'success',
                'title' => '¡Actualizado!',
                'text' => 'El rol se ha actualizado correctamente.'
            ]);
        })->name('roles.update');

        Route::delete('/roles/{role}', function($roleId){
            $role = \Spatie\Permission\Models\Role::findOrFail($roleId);
            $role->delete();

            return redirect()->route('admin.roles.index')->with('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El rol ha sido eliminado.'
            ]);
        })->name('roles.destroy');

        Route::get('/users', function(){ return "Página de Usuarios"; })->name('users.index');
        Route::get('/patients', function(){ return "Página de Pacientes"; })->name('patients.index');
        Route::get('/doctors', function(){ return "Página de Doctores"; })->name('doctors.index');
        Route::get('/appointments', function(){ return "Página de Citas"; })->name('appointments.index');

        Route::get('/administrador', function(){ return view('admin.administrador'); })->name('administrador.index');

        Route::get('/administrador/pedidos', function(){ 
            $today = \Carbon\Carbon::today();
            
            $cashierStats = \App\Models\User::role('Cajeros')
                ->withCount(['orders' => function($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }])
                ->get();

            $orders = \App\Models\Order::with(['user', 'items.product', 'materials'])
                ->whereDate('created_at', $today)
                ->latest()
                ->get();

            return view('admin.administrador.pedidos', compact('orders', 'cashierStats')); 
        })->name('administrador.pedidos');

        Route::get('/administrador/pedidos/pdf', function() {
            $today = \Carbon\Carbon::today();
            $date = $today->format('d/m/Y');
            
            $cashierStats = \App\Models\User::role('Cajeros')
                ->withCount(['orders' => function($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }])
                ->get();

            $orders = \App\Models\Order::with(['user', 'items.product', 'materials'])
                ->whereDate('created_at', $today)
                ->latest()
                ->get();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pedidos-pdf', compact('orders', 'cashierStats', 'date'));
            
            return $pdf->download("pedidos_{$today->format('Y-m-d')}.pdf");
        })->name('administrador.pedidos.pdf');

        Route::post('/administrador/pedidos/email', function() {
            $today = \Carbon\Carbon::today();
            $date = $today->format('d/m/Y');
            
            // Buscar el correo del primer usuario con rol Administrador
            $admin = \App\Models\User::role('Administrador')->first();

            if (!$admin) {
                return back()->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'No se encontró un usuario con el rol de Administrador para recibir el reporte.'
                ]);
            }

            $destinatario = $admin->email;

            $cashierStats = \App\Models\User::role('Cajeros')
                ->withCount(['orders' => function($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }])
                ->get();

            $orders = \App\Models\Order::with(['user', 'items.product', 'materials'])
                ->whereDate('created_at', $today)
                ->latest()
                ->get();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pedidos-pdf', compact('orders', 'cashierStats', 'date'));
            $pdfContent = $pdf->output();

            try {
                \Illuminate\Support\Facades\Mail::to($destinatario)
                    ->send(new \App\Mail\DailyOrdersReport($date, $pdfContent));

                return back()->with('swal', [
                    'icon' => 'success',
                    'title' => 'Reporte Enviado',
                    'text' => "El reporte ha sido enviado exitosamente a: {$destinatario}"
                ]);
            } catch (\Exception $e) {
                return back()->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error de envío',
                    'text' => 'Error técnico: ' . $e->getMessage()
                ]);
            }
        })->name('administrador.pedidos.email');

        Route::get('/administrador/inventario', function(){ 
            $materials = \App\Models\Material::all();
            return view('admin.administrador.inventario', compact('materials')); 
        })->name('administrador.inventario');

        Route::get('/administrador/inventario/create', function(){ 
            return view('admin.administrador.inventario-create'); 
        })->name('administrador.inventario.create');

        Route::post('/administrador/inventario', function(\Illuminate\Http\Request $request){
            $request->validate([
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0'
            ]);

            \App\Models\Material::create($request->all());

            return redirect()->route('admin.administrador.inventario')->with('swal', [
                'icon' => 'success',
                'title' => '¡Agregado!',
                'text' => 'El material se ha agregado al inventario.'
            ]);
        })->name('administrador.inventario.store');

        Route::delete('/administrador/inventario/{material}', function(\App\Models\Material $material){
            $material->delete();
            return redirect()->route('admin.administrador.inventario')->with('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El material ha sido removido del inventario.'
            ]);
        })->name('administrador.inventario.destroy');

        Route::patch('/administrador/inventario/{material}/add', function(\Illuminate\Http\Request $request, \App\Models\Material $material){
            $request->validate([
                'amount' => 'required|integer|min:1'
            ]);

            $material->increment('quantity', $request->amount);

            return redirect()->route('admin.administrador.inventario')->with('swal', [
                'icon' => 'success',
                'title' => 'Stock actualizado',
                'text' => "Se han añadido {$request->amount} unidades a {$material->name}."
            ]);
        })->name('administrador.inventario.add-stock');

        Route::get('/administrador/usuarios', function(){ 
            $users = \App\Models\User::with('roles')->get();
            return view('admin.administrador.usuarios', compact('users')); 
        })->name('administrador.usuarios');

        Route::get('/administrador/usuarios/create', function(){ 
            $roles = \Spatie\Permission\Models\Role::all();
            return view('admin.administrador.usuarios-create', compact('roles')); 
        })->name('administrador.usuarios.create');

        Route::post('/administrador/usuarios', function(\Illuminate\Http\Request $request){
            $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required|exists:roles,name',
                'age' => 'nullable|integer|min:0',
                'phone' => 'nullable|string'
            ]);

            $user = \App\Models\User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'age' => $request->age,
                'phone' => $request->phone,
            ]);

            $user->assignRole($request->role);

            return redirect()->route('admin.administrador.usuarios')->with('swal', [
                'icon' => 'success',
                'title' => '¡Usuario registrado!',
                'text' => 'El usuario se ha creado y se le ha asignado el rol correctamente.'
            ]);
        })->name('administrador.usuarios.store');

        Route::get('/administrador/usuarios/{user}/edit', function(\App\Models\User $user){ 
            $roles = \Spatie\Permission\Models\Role::all();
            return view('admin.administrador.usuarios-edit', compact('user', 'roles')); 
        })->name('administrador.usuarios.edit');

        Route::put('/administrador/usuarios/{user}', function(\Illuminate\Http\Request $request, \App\Models\User $user){
            $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:8',
                'role' => 'required|exists:roles,name',
                'age' => 'nullable|integer|min:0',
                'phone' => 'nullable|string'
            ]);

            $data = [
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'age' => $request->age,
                'phone' => $request->phone,
            ];

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $user->update($data);
            $user->syncRoles([$request->role]);

            return redirect()->route('admin.administrador.usuarios')->with('swal', [
                'icon' => 'success',
                'title' => '¡Actualizado!',
                'text' => 'El usuario se ha actualizado correctamente.'
            ]);
        })->name('administrador.usuarios.update');

        Route::delete('/administrador/usuarios/{user}', function(\App\Models\User $user){
            if(\Illuminate\Support\Facades\Auth::id() == $user->id){
                return redirect()->back()->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'No puedes eliminar tu propia cuenta.'
                ]);
            }

            $user->delete();

            return redirect()->route('admin.administrador.usuarios')->with('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El usuario ha sido eliminado correctamente.'
            ]);
        })->name('administrador.usuarios.destroy');

        Route::get('/administrador/productos', function(){ 
            $products = \App\Models\Product::all();
            return view('admin.administrador.productos', compact('products')); 
        })->name('administrador.productos');

        Route::get('/administrador/productos/create', function(){ 
            return view('admin.administrador.productos-create'); 
        })->name('administrador.productos.create');

        Route::post('/administrador/productos', function(\Illuminate\Http\Request $request){
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048'
            ]);

            $data = $request->only(['name', 'price', 'description']);

            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('products', 'public');
            }

            \App\Models\Product::create($data);

            return redirect()->route('admin.administrador.productos')->with('swal', [
                'icon' => 'success',
                'title' => '¡Producto creado!',
                'text' => 'El producto se ha guardado correctamente.'
            ]);
        })->name('administrador.productos.store');

        Route::get('/administrador/productos/{product}/edit', function(\App\Models\Product $product){ 
            return view('admin.administrador.productos-edit', compact('product')); 
        })->name('administrador.productos.edit');

        Route::put('/administrador/productos/{product}', function(\Illuminate\Http\Request $request, \App\Models\Product $product){
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048'
            ]);

            $data = $request->only(['name', 'price', 'description']);

            if ($request->hasFile('image')) {
                if ($product->image_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
                }
                $data['image_path'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            return redirect()->route('admin.administrador.productos')->with('swal', [
                'icon' => 'success',
                'title' => '¡Actualizado!',
                'text' => 'El producto se ha actualizado correctamente.'
            ]);
        })->name('administrador.productos.update');

        Route::delete('/administrador/productos/{product}', function(\App\Models\Product $product){
            if ($product->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
            }
            $product->delete();

            return redirect()->route('admin.administrador.productos')->with('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El producto ha sido eliminado.'
            ]);
        })->name('administrador.productos.destroy');
    });

    // RUTAS PARA CAJEROS Y ADMINISTRADOR
    Route::middleware(['role:Administrador|Cajeros'])->group(function () {
        Route::get('/cajeros/personal', function(){ 
            $users = \App\Models\User::role('Cajeros')->get();
            return view('admin.cajeros.personal', compact('users')); 
        })->name('cajeros.personal');

        Route::get('/cajeros', function(){ 
            $products = \App\Models\Product::all();
            return view('admin.cajeros.index', compact('products')); 
        })->name('cajeros.index');

        Route::post('/cajeros/pedidos', function(\Illuminate\Http\Request $request){
            $request->validate([
                'customer_name' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $total = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = \App\Models\Product::find($item['id']);
                $price = $product->price;
                $subtotal = $price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price
                ];
            }

            $order = \App\Models\Order::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'customer_name' => $request->customer_name,
                'total' => $total,
                'status' => 'pendiente'
            ]);

            foreach ($orderItems as $details) {
                $order->items()->create($details);
            }

            return redirect()->route('admin.cajeros.index')->with('swal', [
                'icon' => 'success',
                'title' => '¡Pedido realizado!',
                'text' => 'Pedido hecho exitosamente.'
            ]);
        })->name('cajeros.pedidos.store');
    });

    // RUTAS PARA COCINEROS Y ADMINISTRADOR
    Route::middleware(['role:Administrador|Cocineros'])->group(function () {
        Route::get('/cocineros', function(){ 
            $users = \App\Models\User::role('Cocineros')->get();
            return view('admin.cocineros', compact('users')); 
        })->name('cocineros.index');

        Route::get('/cocineros/pedidos', function() {
            $orders = \App\Models\Order::with(['items.product', 'user'])
                ->whereIn('status', ['pendiente', 'en_preparacion', 'listo'])
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->latest()
                ->get();
            
            $materials = \App\Models\Material::all();
            
            return view('admin.cocineros.pedidos', compact('orders', 'materials'));
        })->name('cocineros.pedidos.index');

        Route::patch('/cocineros/pedidos/{order}/status', function(\Illuminate\Http\Request $request, \App\Models\Order $order) {
            if ($request->status == 'listo') {
                $request->validate([
                    'status' => 'required|in:en_preparacion,listo',
                    'materials' => 'required|array',
                    'materials.*' => 'required|integer|min:0'
                ]);

                // Descontar del inventario y registrar uso
                foreach ($request->materials as $materialId => $quantity) {
                    if ($quantity > 0) {
                        $material = \App\Models\Material::find($materialId);
                        if ($material) {
                            if ($material->quantity < $quantity) {
                                return back()->with('swal', [
                                    'icon' => 'error',
                                    'title' => 'Stock insuficiente',
                                    'text' => "No hay suficiente {$material->name} en inventario."
                                ]);
                            }
                            $material->decrement('quantity', $quantity);
                            $order->materials()->attach($materialId, ['quantity' => $quantity]);
                        }
                    }
                }
            } else {
                $request->validate(['status' => 'required|in:en_preparacion,listo']);
            }

            $order->update(['status' => $request->status]);

            return back()->with('swal', [
                'icon' => 'success',
                'title' => '¡Estado actualizado!',
                'text' => 'El pedido ha cambiado de estado y se ha descontado el inventario.'
            ]);
        })->name('cocineros.pedidos.update-status');

        Route::get('/cocineros/inventario', function() {
            $materials = \App\Models\Material::all();
            return view('admin.cocineros.inventario', compact('materials'));
        })->name('cocineros.inventario');
    });

});
 