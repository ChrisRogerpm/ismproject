<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Mesa;
use App\Model\MesaRuta;
use Illuminate\Http\Request;
use App\Model\MesaSupervisor;
use App\Model\CentroOperativo;
use Illuminate\Support\Facades\DB;

class MesaController extends Controller
{
    #region Vista
    public function MesaListarVista()
    {
        return view('Mesa.MesaListar');
    }
    public function MesaRegistrarVista($idCeo)
    {
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Mesa.MesaRegistrar', compact('nombreCeo', 'idCeo'));
    }
    public function MesaEditarVista($idMesa)
    {
        $Mesa = Mesa::with(['CentroOperativo'])->findOrfail($idMesa);
        // $MesaRuta = MesaRuta::where('idMesa', $Mesa->idMesa)->get();
        // $MesaSupervisor = MesaSupervisor::where('idMesa', $Mesa->idMesa)->get();
        return view('Mesa.MesaEditar', compact('Mesa'));
    }
    #endregion
    #region JSON
    public function MesaListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data  = Mesa::MesaListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function MesaRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            DB::begintransaction();
            $ListaRutasRegistrados = $request->input('ListaRutasRegistrados');
            $ListaSupervisorsRegistrados = $request->input('ListaSupervisorsRegistrados');
            $data = Mesa::MesaRegistrar($request);
            foreach ($ListaRutasRegistrados as $lr) {
                $req = new Request([
                    'idMesa' => $data->idMesa,
                    'idRuta' => $lr['idRuta'],
                ]);
                MesaRuta::MesaRutaRegistrar($req);
            }
            foreach ($ListaSupervisorsRegistrados as $ls) {
                $req = new Request([
                    'idMesa' => $data->idMesa,
                    'idSupervisor' => $ls['idSupervisor'],
                ]);
                MesaSupervisor::MesaSupervisorRegistrar($req);
            }
            $respuesta = true;
            $mensaje = "Se ha registrado una Mesa exitosamente";
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
