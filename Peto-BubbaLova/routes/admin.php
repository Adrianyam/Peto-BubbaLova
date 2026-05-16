<?php 

use Illuminate\Support\Facades\Route; 

Route::get('/', function(){ 
    return view('admin.dashboard'); 
})->name('dashboard');

Route::get('/roles', function(){ return "Página de Roles"; })->name('roles.index');
Route::get('/users', function(){ return "Página de Usuarios"; })->name('users.index');
Route::get('/patients', function(){ return "Página de Pacientes"; })->name('patients.index');
Route::get('/doctors', function(){ return "Página de Doctores"; })->name('doctors.index');
Route::get('/appointments', function(){ return "Página de Citas"; })->name('appointments.index');

Route::get('/administrador', function(){ return view('admin.administrador'); })->name('administrador.index');
Route::get('/cocineros', function(){ return view('admin.cocineros'); })->name('cocineros.index');
Route::get('/cajeros', function(){ return view('admin.cajeros'); })->name('cajeros.index');
 