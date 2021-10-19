<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Model\MesaSupervisor;

class MesaSupervisorController extends Controller
{
    #region JSON
    public function MesaSupervisorRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            MesaSupervisor::MesaSupervisorRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado supervisor(es) exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function MesaSupervisorListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data  = MesaSupervisor::MesaSupervisorListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function MesaSupervisorEliminarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            MesaSupervisor::MesaSupervisorEliminar($request);
            $respuesta = true;
            $mensaje = "Se ha eliminado el supervisor exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
