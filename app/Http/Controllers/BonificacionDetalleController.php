<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Model\BonificacionDetalle;

class BonificacionDetalleController extends Controller
{
    public function BonificacionDetalleListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = BonificacionDetalle::BonificacionDetalleListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function BonificacionDetalleEliminarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            BonificacionDetalle::BonificacionDetalleEliminar($request);
            $respuesta = true;
            $mensaje = "Se ha eliminado el producto exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function BonificacionDetalleRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            BonificacionDetalle::BonificacionDetalleRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado el producto a la tabla de bonificaciones exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
}
