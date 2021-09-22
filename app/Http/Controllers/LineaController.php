<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Linea;
use Illuminate\Http\Request;

class LineaController extends Controller
{
    #region Vista
    #endregion
    #region JSON
    public function LineaListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Linea::LineaListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function LineaListarActivosJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Linea::LineaListarActivos($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function LineaRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Linea::LineaRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado una Linea exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function LineaEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Linea::LineaEditar($request);
            $respuesta = true;
            $mensaje = "Se ha editado la Linea exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function LineaBloquearJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Linea::LineaBloquear($request);
            $respuesta = true;
            $mensaje = "Se ha bloqueado la Linea exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function LineaRestablecerJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Linea::LineaRestablecer($request);
            $respuesta = true;
            $mensaje = "Se ha restablecido la Linea exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
