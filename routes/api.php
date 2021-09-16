<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('ReportImportarExcelsJson', 'ReportesController@ReportImportarExcelsJson');

#region CENTRO OPERATIVO
Route::get('CentroOperativoListarJson', 'CentroOperativoController@CentroOperativoListarJson');
Route::get('CentroOperativoListarActivosJson', 'CentroOperativoController@CentroOperativoListarActivosJson');
Route::post('CentroOperativoRegistrarJson', 'CentroOperativoController@CentroOperativoRegistrarJson');
Route::post('CentroOperativoEditarJson', 'CentroOperativoController@CentroOperativoEditarJson');
Route::post('CentroOperativoBloquearJson', 'CentroOperativoController@CentroOperativoBloquearJson');
Route::post('CentroOperativoRestablecerJson', 'CentroOperativoController@CentroOperativoRestablecerJson');
#endregion

#region GESTOR
Route::get('GestorListarJson', 'GestorController@GestorListarJson');
Route::post('GestorRegistrarJson', 'GestorController@GestorRegistrarJson');
Route::post('GestorEditarJson', 'GestorController@GestorEditarJson');
Route::post('GestorBloquearJson', 'GestorController@GestorBloquearJson');
Route::post('GestorRestablecerJson', 'GestorController@GestorRestablecerJson');
#endregion
#region PRODUCTO
Route::get('ProductoListarJson', 'ProductoController@ProductoListarJson');
Route::post('ProductoRegistrarJson', 'ProductoController@ProductoRegistrarJson');
Route::post('ProductoEditarJson', 'ProductoController@ProductoEditarJson');
Route::post('ProductoBloquearJson', 'ProductoController@ProductoBloquearJson');
Route::post('ProductoRestablecerJson', 'ProductoController@ProductoRestablecerJson');
#endregion
#region CLIENTE
Route::get('ClienteListarJson', 'ClienteController@ClienteListarJson');
Route::post('ClienteRegistrarJson', 'ClienteController@ClienteRegistrarJson');
Route::post('ClienteEditarJson', 'ClienteController@ClienteEditarJson');
Route::post('ClienteBloquearJson', 'ClienteController@ClienteBloquearJson');
Route::post('ClienteRestablecerJson', 'ClienteController@ClienteRestablecerJson');
Route::post('ClienteImportarDataJson', 'ClienteController@ClienteImportarDataJson');
#endregion
#region MESA
Route::get('MesaListarJson', 'MesaController@MesaListarJson');
Route::post('MesaRegistrarJson', 'MesaController@MesaRegistrarJson');
#endregion
#region RUTA
Route::get('RutaListarJson', 'RutaController@RutaListarJson');
Route::post('RutaActualizarJson', 'RutaController@RutaActualizarJson');
#endregion
#region SUPERVISOR
Route::get('SupervisorListarJson', 'SupervisorController@SupervisorListarJson');
#endregion
#region MES_ARUTA
Route::get('MesaRutaListarJson', 'MesaRutaController@MesaRutaListarJson');
Route::post('MesaRutaRegistrarJson', 'MesaRutaController@MesaRutaRegistrarJson');
Route::post('MesaRutaEliminarJson', 'MesaRutaController@MesaRutaEliminarJson');
#endregion
#region MESA_SUPERVISOR
Route::get('MesaSupervisorListarJson', 'MesaSupervisorController@MesaSupervisorListarJson');
Route::post('MesaSupervisorEliminarJson', 'MesaSupervisorController@MesaSupervisorEliminarJson');
#endregion
#region GESTOR_PRODUCTO
Route::get('GestorProductoListarJson', 'GestorProductoController@GestorProductoListarJson');
Route::post('GestorProductoRegistrarJson', 'GestorProductoController@GestorProductoRegistrarJson');
Route::post('GestorProductoEliminarJson', 'GestorProductoController@GestorProductoEliminarJson');
Route::post('ProductoImportarDataJson', 'ProductoController@ProductoImportarDataJson');
#endregion
#region GESTOR_RUTA
Route::get('GestorRutaListarJson', 'GestorRutaController@GestorRutaListarJson');
Route::post('GestorRutaRegistrarJson', 'GestorRutaController@GestorRutaRegistrarJson');
Route::post('GestorRutaEliminarJson', 'GestorRutaController@GestorRutaEliminarJson');
#endregion
#region GESTOR_SUPERVISOR
Route::get('GestorSupervisorListarJson', 'GestorSupervisorController@GestorSupervisorListarJson');
Route::post('GestorSupervisorRegistrarJson', 'GestorSupervisorController@GestorSupervisorRegistrarJson');
Route::post('GestorSupervisorEliminarJson', 'GestorSupervisorController@GestorSupervisorEliminarJson');
#endregion
#region LINEA
Route::get('LineaListarJson', 'LineaController@LineaListarJson');
Route::get('LineaListarActivosJson', 'LineaController@LineaListarActivosJson');
Route::post('LineaRegistrarJson', 'LineaController@LineaRegistrarJson');
Route::post('LineaEditarJson', 'LineaController@LineaEditarJson');
Route::post('LineaBloquearJson', 'LineaController@LineaBloquearJson');
Route::post('LineaRestablecerJson', 'LineaController@LineaRestablecerJson');
#endregion
