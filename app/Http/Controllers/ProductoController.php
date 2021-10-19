<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Producto;
use Illuminate\Http\Request;
use App\Model\CentroOperativo;

class ProductoController extends Controller
{
    #region Vista
    public function ProductoListarVista()
    {
        return view('Producto.ProductoListar');
    }
    public function ProductoRegistrarVista($idCeo)
    {
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Producto.ProductoRegistrar', compact('nombreCeo', 'idCeo'));
    }
    public function ProductoEditarVista($idProducto)
    {
        $Producto = Producto::findOrfail($idProducto);
        $nombreCeo = CentroOperativo::findOrfail($Producto->idCeo)->nombreCeo;
        $idCeo = $Producto->idCeo;
        return view('Producto.ProductoEditar', compact('Producto', 'nombreCeo', 'idCeo'));
    }
    #endregion
    #region JSON
    public function ProductoListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Producto::ProductoListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    // ProductoCodigoPadreListar
    public function ProductoCodigoPadreListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Producto::ProductoCodigoPadreListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function ProductoRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Producto::ProductoRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado un Producto exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ProductoEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Producto::ProductoEditar($request);
            $respuesta = true;
            $mensaje = "Se ha editado el Producto exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ProductoBloquearJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Producto::ProductoBloquear($request);
            $respuesta = true;
            $mensaje = "Se ha bloqueado el Producto exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ProductoRestablecerJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Producto::ProductoRestablecer($request);
            $respuesta = true;
            $mensaje = "Se ha restablecido el Producto exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ProductoImportarDataJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        $data = "";
        try {
            $extension = $request->file('productoExcel')->extension();
            if ($extension == "txt") {
                $data = Producto::ProductoImportarData($request);
                if ($data->respuesta) {
                    $respuesta = true;
                    $mensaje = "Se ha importado los productos exitosamente";
                } else {
                    $mensaje = $data->mensaje;
                }
            } else {
                $mensaje = "El formato del archivo no es CSV";
            }
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
