<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Model\ComisionDetalle;

class ComisionDetalleController extends Controller
{
    #region JSON
    public function ComisionDetalleListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = ComisionDetalle::ComisionDetalleListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    // ComisionDetalleRegistrar
    public function ComisionDetalleRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            ComisionDetalle::ComisionDetalleRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado los productos exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ComisionDetalleEliminarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            ComisionDetalle::ComisionDetalleEliminar($request);
            $respuesta = true;
            $mensaje = "Se ha eliminado los productos exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
