<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Model\GestorSupervisor;

class GestorSupervisorController extends Controller
{
    public function GestorSupervisorListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = GestorSupervisor::GestorSupervisorListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function GestorSupervisorRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            GestorSupervisor::GestorSupervisorRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado el Supervisor exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function GestorSupervisorEliminarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            GestorSupervisor::GestorSupervisorEliminar($request);
            $respuesta = true;
            $mensaje = "Se ha eliminado el Supervisor exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
}
