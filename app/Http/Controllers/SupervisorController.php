<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Supervisor;
use Illuminate\Http\Request;
use App\Model\CentroOperativo;

class SupervisorController extends Controller
{
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
        return view('Supervisor.SupervisorEditar', compact('Supervisor'));
    }
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
}
