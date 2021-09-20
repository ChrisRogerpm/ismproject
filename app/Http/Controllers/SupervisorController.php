<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Supervisor;
use Illuminate\Http\Request;
use App\Model\CentroOperativo;

class SupervisorController extends Controller
{
    #region Vista
    public function SupervisorListarVista()
    {
        return view('Supervisor.SupervisorListar');
    }
    public function SupervisorRegistrarVista($idCeo)
    {
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Supervisor.SupervisorRegistrar', compact('nombreCeo', 'idCeo'));
    }
    public function SupervisorEditarVista($idSupervisor)
    {
        $Supervisor = Supervisor::findOrfail($idSupervisor);
        $nombreCeo = CentroOperativo::findOrfail($Supervisor->idCeo)->nombreCeo;
        $idCeo = $Supervisor->idCeo;
        return view('Supervisor.SupervisorEditar', compact('Supervisor', 'nombreCeo', 'idCeo'));
    }
    #endregion
    #region JSON
    public function SupervisorListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data  = Supervisor::SupervisorListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function SupervisorRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Supervisor::SupervisorRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado un Supervisor exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function SupervisorEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Supervisor::SupervisorEditar($request);
            $respuesta = true;
            $mensaje = "Se ha editado el Supervisor exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
