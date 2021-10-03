<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Comision;
use Illuminate\Http\Request;
use App\Model\CentroOperativo;
use App\Model\ComisionDetalle;
use Illuminate\Support\Facades\DB;

class ComisionController extends Controller
{
    #region Vista
    public function ComisionListarVista()
    {
        return view('Comision.ComisionListar');
    }
    public function ComisionRegistrarVista($idCeo)
    {
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Comision.ComisionRegistrar', compact('nombreCeo', 'idCeo'));
    }
    public function ComisionEditarVista($idComision)
    {
        $Comision = Comision::findOrfail($idComision);
        $idCeo = $Comision->idCeo;
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Comision.ComisionEditar', compact('Comision', 'idCeo', 'nombreCeo'));
    }
    #endregion
    #region JSON
    public function ComisionListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Comision::ComisionListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function ComisionRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            $data = Comision::ComisionRegistrar($request);
            ComisionDetalle::ComisionDetalleRegistrarLista($request, $data);
            $respuesta = true;
            $mensaje = "Se ha registrado una Comision exitosamente";
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ComisionEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Comision::ComisionEditar($request);
            ComisionDetalle::ComisionDetalleEditarLista($request);
            $respuesta = true;
            $mensaje = "Se ha editado la Comision exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ComisionActivarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Comision::ComisionActivar($request);
            $respuesta = true;
            $mensaje = "Se ha activado la Comisión exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public static function ComisionImportarDataJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        $data = "";
        try {
            $extension = $request->file('archivoComision')->extension();
            if ($extension == "txt") {
                $data = Comision::ComisionImportarData($request);
                $respuesta = true;
                $mensaje = "Se ha importado la información exitosamente";
            } else {
                $mensaje = "El formato del archivo no es CSV";
            }
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje, 'data' => $data]);
    }
    #endregion
}
