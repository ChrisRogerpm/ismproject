<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Ruta;
use Illuminate\Http\Request;
use App\Model\CentroOperativo;

class RutaController extends Controller
{
    public function RutaListarVista()
    {
        return view('Ruta.RutaListar');
    }
    public function RutaRegistrarVista($idCeo)
    {
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Ruta.RutaRegistrar', compact('nombreCeo', 'idCeo'));
    }
    public function RutaEditarVista($idRuta)
    {
        $Ruta = Ruta::findOrfail($idRuta);
        $nombreCeo = CentroOperativo::findOrfail($Ruta->idCeo)->nombreCeo;
        return view('Ruta.RutaEditar', compact('Ruta', 'nombreCeo'));
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
    public function RutaRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Ruta::RutaRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado la ruta exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function RutaEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Ruta::RutaEditar($request);
            $respuesta = true;
            $mensaje = "Se ha editado la ruta exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
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
