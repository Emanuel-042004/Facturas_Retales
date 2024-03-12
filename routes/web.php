<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacturasController;
use App\Http\Controllers\PendientesController;
use App\Http\Controllers\EntregadosController;
use App\Http\Controllers\RecibidosController;
use App\Http\Controllers\CargadosController;
use App\Http\Controllers\ReembolsosController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/subir', function () {
    return view('subir');
});

Route::get('/index', [FacturasController::class, 'index'])->name('facturas.index');


Route::post('/facturas', [FacturasController::class, 'store'])->name('facturas.store');


Route::get('/pendientes', [PendientesController::class, 'index'])->name('pendientes.index');

Route::get('/pendientes', [PendientesController::class, 'update'])->name('pendientes.update');
Route::post('cambiar-tipo-facturas', [PendientesController::class, 'cambiarTipoFacturas'])->name('cambiar_tipo_facturas');
Route::post('cambiar-tipo-facturas', [PendientesController::class, 'crearReembolso'])->name('crear_reembolso');


Route::get('/reembolsos', [ReembolsosController::class, 'index'])->name('reembolsos.index');


Route::post('/cargar-factura/{id}', [PendientesController::class, 'cargarFactura'])->name('cargar_factura');
Route::get('/eliminar-factura/{id}', [PendientesController::class, 'eliminarFactura'])->name('eliminar_factura');
Route::get('/asignar_area/{id}', [PendientesController::class, 'asignarArea'])->name('asignar_area');
Route::post('/realizar-acciones', [PendientesController::class, 'eliminarSeleccion'])->name('eliminar_seleccion');
Route::get('/pendientes/{id}/rechazar', [PendientesController::class, 'rechazar'])->name('pendientes.rechazar');
Route::post('/pendientes/{id}/aprobar', [PendientesController::class, 'aprobar'])->name('pendientes.aprobar');
Route::post('/facturas/{id}/editar-anexo/{numero_anexo}', [PendientesController::class, 'editarAnexo'])->name('editar_anexo');

Route::get('/cargados', [CargadosController::class, 'index'])->name('cargados.index');
Route::get('/cargados/{id}/rechazar', [CargadosController::class, 'rechazar'])->name('cargados.rechazar');
Route::post('/cargados/{id}/entregar', [CargadosController::class, 'entregar'])->name('cargados.entregar');
Route::post('/causar-factura/{id}', [CargadosController::class, 'causarFactura'])->name('causar_factura');

Route::get('/entregados', [EntregadosController::class, 'index'])->name('entregados.index');
Route::post('/recibir-factura/{id}', [EntregadosController::class, 'recibirFactura']);
Route::get('/eliminar-factura/{id}', [EntregadosController::class, 'eliminarFactura'])->name('eliminar_factura');

Route::get('/recibidos', [RecibidosController::class, 'index'])->name('recibidos.index');

Route::view('/facturas', 'facturas.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/index', [FacturasController::class, 'index'])->name('facturas.index');
    Route::post('/facturas', [FacturasController::class, 'store'])->name('facturas.store');
    
    Route::get('/pendientes', [PendientesController::class, 'index'])->name('pendientes.index');
    Route::post('/entregar-factura/{id}', [PendientesController::class, 'entregarFactura'])->name('entregar_factura');
    Route::get('/eliminar-factura/{id}', [PendientesController::class, 'eliminarFactura'])->name('eliminar_factura');
    
    Route::get('/entregados', [EntregadosController::class, 'index'])->name('entregados.index');
    Route::post('/recibir-factura/{id}', [EntregadosController::class, 'recibirFactura']);
    Route::get('/eliminar-factura/{id}', [EntregadosController::class, 'eliminarFactura'])->name('eliminar_factura');
    
    Route::get('/recibidos', [RecibidosController::class, 'index'])->name('recibidos.index');
});

Route::post('import-list-excel', [FacturasController::class, 'importExcel'])->name('invoices.import.excel');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


