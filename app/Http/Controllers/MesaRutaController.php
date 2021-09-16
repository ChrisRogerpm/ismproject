<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\MesaRuta;
use Illuminate\Http\Request;

class MesaRutaController extends Controller
{
    #region JSON
    public function MesaRutaListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data  = MesaRuta::MesaRutaListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function MesaRutaRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            $ListaRutasTabla = $request->input('ListaRutasTabla');
            foreach ($ListaRutasTabla as $rf) {
                $obj = MesaRuta::where('idMesa', $request->input('idMesa'))->where('idRuta', $rf['idRuta'])->first();
                if ($obj == null) {
                    $req = new Request([
                        'idMesa' => $request->input('idMesa'),
                        'idRuta' => $rf['idRuta'],
                    ]);
                    MesaRuta::MesaRutaRegistrar($req);
                }
            }
            $respuesta = true;
            $mensaje = "Se ha registrado nueva(s) rutas exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function MesaRutaEliminarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            MesaRuta::MesaRutaEliminar($request);
            $respuesta = true;
            $mensaje = "Se ha eliminado la ruta exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
