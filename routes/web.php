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

Route::middleware(['guest'])->group(function () {
    Route::get('/', 'AutenticacionController@LoginVista')->name('login');
    Route::post('ValidarLoginJson', 'AutenticacionController@ValidarLoginJson');
});
// Route::get('/', 'ReportesController@ReportePedidosListarVista')->name('Conversion.Pedido');
Route::post('CerrarSesionJson', 'AutenticacionController@CerrarSesionJson')->name('CerrarSesion');

Route::middleware(['auth'])->group(function () {
    Route::get('CambiarContrasenia', 'AutenticacionController@CambiarContraseniaVista')->name('CambiarContrasenia.Listar');
    Route::post('PerfilCambiarContraseniaJson', 'AutenticacionController@PerfilCambiarContraseniaJson');
});
#region VISTA
Route::middleware(['auth', 'rolpermisos.middleware'])->group(function () {
    Route::get('ReporteProducto', 'ReportesController@ReporteProductoMasVendidoVista')->name('Reporte.ProductoMasVendido');
    Route::get('ReporteNroPedido', 'ReportesController@ReporteNroPedidoMasVendidoVista')->name('Reporte.NroPedidoMasVendido');
    Route::get('ReporteComisionesGestores', 'ReportesController@ReporteComisionGestoresVista')->name('Reporte.ComisionGestores');

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
    Route::get('RegistrarRuta/{idCeo}', 'RutaController@RutaRegistrarVista');
    Route::get('EditarRuta/{idRuta}', 'RutaController@RutaEditarVista');

    Route::get('Mesa', 'MesaController@MesaListarVista')->name('Mesa.Listar');
    Route::get('RegistrarMesa/{idCeo}', 'MesaController@MesaRegistrarVista');
    Route::get('EditarMesa/{idMesa}', 'MesaController@MesaEditarVista');

    Route::get('Producto', 'ProductoController@ProductoListarVista')->name('Producto.Listar');
    Route::get('RegistrarProducto/{idCeo}', 'ProductoController@ProductoRegistrarVista');
    Route::get('EditarProducto/{idProducto}', 'ProductoController@ProductoEditarVista');

    Route::get('Linea', 'LineaController@LineaListarVista')->name('Linea.Listar');
    Route::get('RegistrarLinea/{idCeo}', 'LineaController@LineaRegistrarVista');
    Route::get('EditarLinea/{idLinea}', 'LineaController@LineaEditarVista');

    Route::get('Bonificacion', 'BonificacionController@BonificacionListarVista')->name('Bonificacion.Listar');
    Route::get('RegistrarBonificacion/{idCeo}', 'BonificacionController@BonificacionRegistrarVista');
    Route::get('EditarBonificacion/{idBonificacion}', 'BonificacionController@BonificacionEditarVista');

    Route::get('Supervisor', 'SupervisorController@SupervisorListarVista')->name('Supervisor.Listar');
    Route::get('RegistrarSupervisor/{idCeo}', 'SupervisorController@SupervisorRegistrarVista');
    Route::get('EditarSupervisor/{idSupervisor}', 'SupervisorController@SupervisorEditarVista');

    Route::get('Pedido', 'PedidoController@PedidoListarVista')->name('Pedido.Listar');

    Route::get('Comision', 'ComisionController@ComisionListarVista')->name('Comision.Listar');
    Route::get('RegistrarComision/{idCeo}', 'ComisionController@ComisionRegistrarVista');
    Route::get('EditarComision/{idComision}', 'ComisionController@ComisionEditarVista');

    Route::get('Usuario', 'UsuarioController@UsuarioListarVista')->name('Usuario.Listar');
    Route::get('RegistrarUsuario', 'UsuarioController@UsuarioRegistrarVista');
    Route::get('EditarUsuario/{idUsuario}', 'UsuarioController@UsuarioEditarVista');

    Route::get('Rol', 'RolController@RolListarVista')->name('Rol.Listar');
    Route::get('RegistrarRol', 'RolController@RolRegistrarVista');
    Route::get('EditarRol/{idRol}', 'RolController@RolEditarVista');
});
#end
#region JSON POST
Route::middleware(['auth', 'rolpermisos.middleware'])->group(function () {
    #region PEDIDO
    Route::post('ReportImportarExcelsJson', 'ReportesController@ReportImportarExcelsJson');
    #endregion
    #region CENTRO OPERATIVO
    Route::post('CentroOperativoRegistrarJson', 'CentroOperativoController@CentroOperativoRegistrarJson');
    Route::post('CentroOperativoEditarJson', 'CentroOperativoController@CentroOperativoEditarJson');
    Route::post('CentroOperativoBloquearJson', 'CentroOperativoController@CentroOperativoBloquearJson');
    Route::post('CentroOperativoRestablecerJson', 'CentroOperativoController@CentroOperativoRestablecerJson');
    #endregion
    #region GESTOR
    Route::post('GestorRegistrarJson', 'GestorController@GestorRegistrarJson');
    Route::post('GestorEditarJson', 'GestorController@GestorEditarJson');
    Route::post('GestorBloquearJson', 'GestorController@GestorBloquearJson');
    Route::post('GestorRestablecerJson', 'GestorController@GestorRestablecerJson');
    Route::post('GestorImportarDataJson', 'GestorController@GestorImportarDataJson');
    Route::post('GestorEliminarJson', 'GestorController@GestorEliminarJson');
    #endregion
    #region PRODUCTO
    Route::post('ProductoRegistrarJson', 'ProductoController@ProductoRegistrarJson');
    Route::post('ProductoEditarJson', 'ProductoController@ProductoEditarJson');
    Route::post('ProductoBloquearJson', 'ProductoController@ProductoBloquearJson');
    Route::post('ProductoRestablecerJson', 'ProductoController@ProductoRestablecerJson');
    #endregion
    #region CLIENTE
    Route::post('ClienteRegistrarJson', 'ClienteController@ClienteRegistrarJson');
    Route::post('ClienteEditarJson', 'ClienteController@ClienteEditarJson');
    Route::post('ClienteBloquearJson', 'ClienteController@ClienteBloquearJson');
    Route::post('ClienteRestablecerJson', 'ClienteController@ClienteRestablecerJson');
    Route::post('ClienteImportarDataJson', 'ClienteController@ClienteImportarDataJson');
    #endregion
    #region MESA
    Route::post('MesaRegistrarJson', 'MesaController@MesaRegistrarJson');
    Route::post('MesaEditarJson', 'MesaController@MesaEditarJson');
    #endregion
    #region RUTA
    Route::post('RutaActualizarJson', 'RutaController@RutaActualizarJson');
    Route::post('RutaRegistrarJson', 'RutaController@RutaRegistrarJson');
    Route::post('RutaEditarJson', 'RutaController@RutaEditarJson');
    #endregion
    #region SUPERVISOR
    Route::post('SupervisorRegistrarJson', 'SupervisorController@SupervisorRegistrarJson');
    Route::post('SupervisorEditarJson', 'SupervisorController@SupervisorEditarJson');
    #endregion
    #region MES_ARUTA
    Route::post('MesaRutaRegistrarJson', 'MesaRutaController@MesaRutaRegistrarJson');
    Route::post('MesaRutaEliminarJson', 'MesaRutaController@MesaRutaEliminarJson');
    #endregion
    #region MESA_SUPERVISOR
    Route::post('MesaSupervisorEliminarJson', 'MesaSupervisorController@MesaSupervisorEliminarJson');
    Route::post('MesaSupervisorRegistrarJson', 'MesaSupervisorController@MesaSupervisorRegistrarJson');
    #endregion
    #region GESTOR_PRODUCTO
    Route::get('GestorProductoListarJson', 'GestorProductoController@GestorProductoListarJson');
    Route::post('GestorProductoRegistrarJson', 'GestorProductoController@GestorProductoRegistrarJson');
    Route::post('GestorProductoEliminarJson', 'GestorProductoController@GestorProductoEliminarJson');
    Route::post('ProductoImportarDataJson', 'ProductoController@ProductoImportarDataJson');
    #endregion
    #region GESTOR_RUTA
    Route::post('GestorRutaRegistrarJson', 'GestorRutaController@GestorRutaRegistrarJson');
    Route::post('GestorRutaEliminarJson', 'GestorRutaController@GestorRutaEliminarJson');
    #endregion
    #region GESTOR_SUPERVISOR
    Route::post('GestorSupervisorRegistrarJson', 'GestorSupervisorController@GestorSupervisorRegistrarJson');
    Route::post('GestorSupervisorEliminarJson', 'GestorSupervisorController@GestorSupervisorEliminarJson');
    #endregion
    #region LINEA
    Route::post('LineaRegistrarJson', 'LineaController@LineaRegistrarJson');
    Route::post('LineaEditarJson', 'LineaController@LineaEditarJson');
    Route::post('LineaBloquearJson', 'LineaController@LineaBloquearJson');
    Route::post('LineaRestablecerJson', 'LineaController@LineaRestablecerJson');
    #endregion
    #region BONIFICACION
    Route::post('BonificacionRegistrarJson', 'BonificacionController@BonificacionRegistrarJson');
    Route::post('BonificacionEditarJson', 'BonificacionController@BonificacionEditarJson');
    Route::post('BonificacionActivarJson', 'BonificacionController@BonificacionActivarJson');
    Route::post('BonificacionImportarDataJson', 'BonificacionController@BonificacionImportarDataJson');
    #endregion
    #region BONIFICACION_DETALLE
    Route::post('BonificacionDetalleEliminarJson', 'BonificacionDetalleController@BonificacionDetalleEliminarJson');
    Route::post('BonificacionDetalleRegistrarJson', 'BonificacionDetalleController@BonificacionDetalleRegistrarJson');
    #endregion
    #region Pedido
    Route::post('PedidoImportarDataJson', 'PedidoController@PedidoImportarDataJson');
    #endregion
    #region COMISION
    Route::post('ComisionRegistrarJson', 'ComisionController@ComisionRegistrarJson');
    Route::post('ComisionEditarJson', 'ComisionController@ComisionEditarJson');
    Route::post('ComisionActivarJson', 'ComisionController@ComisionActivarJson');
    Route::post('ComisionImportarDataJson', 'ComisionController@ComisionImportarDataJson');
    #endregion
    #region COMISION DETALLE
    Route::post('ComisionDetalleRegistrarJson', 'ComisionDetalleController@ComisionDetalleRegistrarJson');
    Route::post('ComisionDetalleEliminarJson', 'ComisionDetalleController@ComisionDetalleEliminarJson');
    #endregion
    #region USUARIO
    Route::post('UsuarioRegistrarJson', 'UsuarioController@UsuarioRegistrarJson');
    Route::post('UsuarioEditarJson', 'UsuarioController@UsuarioEditarJson');
    Route::post('UsuarioBloquearJson', 'UsuarioController@UsuarioBloquearJson');
    Route::post('UsuarioRestablecerJson', 'UsuarioController@UsuarioRestablecerJson');
    #endregion
    #region ROL
    Route::post('RolRegistrarJson', 'RolController@RolRegistrarJson');
    Route::post('RolEditarJson', 'RolController@RolEditarJson');
    Route::post('RolEliminarJson', 'RolController@RolEliminarJson');
    #endregion
});
#endregion
#region JSON GET
#region CENTRO OPERATIVO
Route::get('CentroOperativoListarJson', 'CentroOperativoController@CentroOperativoListarJson');
Route::get('CentroOperativoListarActivosJson', 'CentroOperativoController@CentroOperativoListarActivosJson');
#endregion

#region GESTOR
Route::get('GestorListarJson', 'GestorController@GestorListarJson');
#endregion
#region PRODUCTO
Route::get('ProductoListarJson', 'ProductoController@ProductoListarJson');
Route::get('ProductoCodigoPadreListarJson', 'ProductoController@ProductoCodigoPadreListarJson');
#endregion
#region CLIENTE
Route::get('ClienteListarJson', 'ClienteController@ClienteListarJson');
#endregion
#region MESA
Route::get('MesaListarJson', 'MesaController@MesaListarJson');
#endregion
#region RUTA
Route::get('RutaListarJson', 'RutaController@RutaListarJson');
#endregion
#region SUPERVISOR
Route::get('SupervisorListarJson', 'SupervisorController@SupervisorListarJson');
#endregion
#region MES_ARUTA
Route::get('MesaRutaListarJson', 'MesaRutaController@MesaRutaListarJson');
#endregion
#region MESA_SUPERVISOR
Route::get('MesaSupervisorListarJson', 'MesaSupervisorController@MesaSupervisorListarJson');
#endregion
#region GESTOR_PRODUCTO
Route::get('GestorProductoListarJson', 'GestorProductoController@GestorProductoListarJson');
#endregion
#region GESTOR_RUTA
Route::get('GestorRutaListarJson', 'GestorRutaController@GestorRutaListarJson');
#endregion
#region GESTOR_SUPERVISOR
Route::get('GestorSupervisorListarJson', 'GestorSupervisorController@GestorSupervisorListarJson');
#endregion
#region LINEA
Route::get('LineaListarJson', 'LineaController@LineaListarJson');
Route::get('LineaListarActivosJson', 'LineaController@LineaListarActivosJson');
#endregion
#region BONIFICACION
Route::get('BonificacionListarJson', 'BonificacionController@BonificacionListarJson');
#endregion

#region BONIFICACION_DETALLE
Route::get('BonificacionDetalleListarJson', 'BonificacionDetalleController@BonificacionDetalleListarJson');
#endregion
#region Pedido
Route::get('PedidoListarJson', 'PedidoController@PedidoListarJson');
Route::get('PedidoDetalleListarJson', 'PedidoController@PedidoDetalleListarJson');
#endregion
#region Download Excel
Route::get('GestoresDownload', 'ExcelController@GestoresDownload');
Route::get('BonificacionesDownload', 'ExcelController@BonificacionesDownload');
Route::get('GestorExcelDownload', 'ExcelController@GestorExcelDownload');
Route::get('PedidoMasVendidoDownload', 'ExcelController@PedidoMasVendidoDownload');
Route::get('ProductoDownload', 'ExcelController@ProductoDownload');
#endregion
#region Reporte
Route::get('ReporteProductoListarJson', 'ReportesController@ReporteProductoListarJson');
Route::get('ReporteNroPedidoListarJson', 'ReportesController@ReporteNroPedidoListarJson');
Route::get('ReporteComisionesGestoresJson', 'ReportesController@ReporteComisionesGestoresJson');
Route::get('PedidoDetalleListarProductosGestorJson', 'ReportesController@PedidoDetalleListarProductosGestorJson');
Route::get('ReporteGestoresBonificacionCentroOperativoJson', 'ReportesController@ReporteGestoresBonificacionCentroOperativoJson');
#endregion
#region COMISION
Route::get('ComisionListarJson', 'ComisionController@ComisionListarJson');
#endregion
#region COMISION DETALLE
Route::get('ComisionDetalleListarJson', 'ComisionDetalleController@ComisionDetalleListarJson');
#endregion
#region USUARIO
Route::get('UsuarioListarJson', 'UsuarioController@UsuarioListarJson');
#endregion
#region ROL
Route::get('RolListarJson', 'RolController@RolListarJson');
#endregion
#region PERMISOS
Route::get('PermisoListarJson', 'PermisoController@PermisoListarJson');
    #endregion
#endregion
