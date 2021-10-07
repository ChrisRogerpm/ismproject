<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Model\GestorProducto;

class GestorProductoController extends Controller
{
    public function GestorProductoListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = GestorProducto::GestorProductoListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function GestorProductoRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            GestorProducto::GestorProductoRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado el producto exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function GestorProductoEliminarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            GestorProducto::GestorProductoEliminar($request);
            $respuesta = true;
            $mensaje = "Se ha eliminado el producto exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
}
