<?php 

use Illuminate\Support\Facades\Route; 

Route::get('/', function(){ 
    return view('admin.dashboard'); 
})->name('dashboard');

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
    return view('admin.administrador.pedidos'); 
})->name('administrador.pedidos');

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
    // No permitir que un usuario se borre a sí mismo si es el admin actual
    if(auth()->id() == $user->id){
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

Route::get('/cocineros', function(){ return view('admin.cocineros'); })->name('cocineros.index');
Route::get('/cajeros', function(){ return view('admin.cajeros'); })->name('cajeros.index');
 