<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ReportesController@ReportePedidosListarVista')->name('Conversion.Pedido');


Route::get('CentroOperativo', 'CentroOperativoController@CentroOperativoListarVista')->name('CentroOperativo.Listar');
Route::get('RegistrarCentroOperativo', 'CentroOperativoController@CentroOperativoRegistrarVista');
Route::get('EditarCentroOperativo/{idCeo}', 'CentroOperativoController@CentroOperativoEditarVista');

Route::get('Gestor', 'GestorController@GestorListarVista')->name('Gestor.Listar');
Route::get('RegistrarGestor/{idCeo}', 'GestorController@GestorRegistrarVista');
Route::get('EditarGestor/{idGestor}', 'GestorController@GestorEditarVista');


Route::get('Cliente', 'ClienteController@ClienteListarVista')->name('Cliente.Listar');
Route::get('RegistrarCliente/{idCeo}', 'ClienteController@ClienteRegistrarVista');
Route::get('EditarCliente/{idCliente}', 'ClienteController@ClienteEditarVista');

Route::get('Ruta', 'RutaController@RutaListarVista')->name('Ruta.Listar');

Route::get('Mesa', 'MesaController@MesaListarVista')->name('Mesa.Listar');
Route::get('RegistrarMesa/{idCeo}', 'MesaController@MesaRegistrarVista');
Route::get('EditarMesa/{idMesa}', 'MesaController@MesaEditarVista');

Route::get('Producto', 'ProductoController@ProductoListarVista')->name('Producto.Listar');
Route::get('RegistrarProducto/{idCeo}', 'ProductoController@ProductoRegistrarVista');
Route::get('EditarProducto/{idProducto}', 'ProductoController@ProductoEditarVista');

Route::get('Linea', 'LineaController@LineaListarVista')->name('Linea.Listar');
Route::get('RegistrarLinea/{idCeo}', 'LineaController@LineaRegistrarVista');
Route::get('EditarLinea/{idLinea}', 'LineaController@LineaEditarVista');
