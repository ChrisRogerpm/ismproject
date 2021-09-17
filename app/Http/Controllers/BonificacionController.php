<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Bonificacion;
use Illuminate\Http\Request;
use App\Model\CentroOperativo;
use App\Model\BonificacionDetalle;
use Illuminate\Support\Facades\DB;

class BonificacionController extends Controller
{
    #region Vista
    public function BonificacionListarVista()
    {
        return view('Bonificacion.BonificacionListar');
    }
    public function BonificacionRegistrarVista($idCeo)
    {
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Bonificacion.BonificacionRegistrar', compact('nombreCeo', 'idCeo'));
    }
    public function BonificacionEditarVista($idBonificacion)
    {
        $Bonificacion = Bonificacion::findOrfail($idBonificacion);
        $nombreCeo = CentroOperativo::findOrfail($Bonificacion->idCeo)->nombreCeo;
        $idCeo = $Bonificacion->idCeo;
        return view('Bonificacion.BonificacionEditar', compact('Bonificacion', 'nombreCeo', 'idCeo'));
    }
    #endregion
    #region JSON
    public function BonificacionListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Bonificacion::BonificacionListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function BonificacionRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            $data = Bonificacion::BonificacionRegistrar($request);
            BonificacionDetalle::BonificacionDetalleRegistrarLista($request, $data);
            $respuesta = true;
            $mensaje = "Se ha registrado una Bonificacion exitosamente";
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function BonificacionEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Bonificacion::BonificacionEditar($request);
            BonificacionDetalle::BonificacionDetalleEditarLista($request);
            $respuesta = true;
            $mensaje = "Se ha editado la Bonificacion exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function BonificacionActivarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Bonificacion::BonificacionActivar($request);
            $respuesta = true;
            $mensaje = "Se ha activado la Bonificacion exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
