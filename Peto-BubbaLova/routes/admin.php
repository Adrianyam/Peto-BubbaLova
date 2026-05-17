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
Route::get('/cocineros', function(){ return view('admin.cocineros'); })->name('cocineros.index');
Route::get('/cajeros', function(){ return view('admin.cajeros'); })->name('cajeros.index');
 