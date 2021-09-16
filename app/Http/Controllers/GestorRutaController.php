<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\GestorRuta;
use Illuminate\Http\Request;

class GestorRutaController extends Controller
{
    public function GestorRutaListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = GestorRuta::GestorRutaListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function GestorRutaRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            GestorRuta::GestorRutaRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado la Ruta exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function GestorRutaEliminarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            GestorRuta::GestorRutaEliminar($request);
            $respuesta = true;
            $mensaje = "Se ha eliminado la Ruta exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
}
