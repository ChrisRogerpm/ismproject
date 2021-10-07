<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function RutaListarVista()
    {
        return view('Ruta.RutaListar');
    }
    public function RutaListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data  = Ruta::RutaListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function RutaActualizarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Ruta::RutaActualizar($request);
            $respuesta = true;
            $mensaje = "Se ha actualizado las rutas exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
}
