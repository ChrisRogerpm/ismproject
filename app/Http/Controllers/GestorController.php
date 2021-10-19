<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Gestor;
use App\Model\GestorRuta;
use Illuminate\Http\Request;
use App\Model\GestorProducto;
use App\Model\CentroOperativo;
use App\Model\GestorSupervisor;
use Illuminate\Support\Facades\DB;

class GestorController extends Controller
{
    #region Vista
    public function GestorListarVista()
    {
        return view('Gestor.GestorListar');
    }
    public function GestorRegistrarVista($idCeo)
    {
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Gestor.GestorRegistrar', compact('nombreCeo', 'idCeo'));
    }
    public function GestorEditarVista($idGestor)
    {
        $Gestor = Gestor::findOrfail($idGestor);
        $idCeo = $Gestor->idCeo;
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Gestor.GestorEditar', compact('Gestor', 'idCeo', 'nombreCeo'));
    }
    #endregion
    #region JSON
    public function GestorListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Gestor::GestorListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function GestorRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            $data = Gestor::GestorRegistrar($request);
            GestorProducto::GestorProductoRegistrarLista($request, $data);
            GestorRuta::GestorRutaRegistrarLista($request, $data);
            GestorSupervisor::GestorSupervisorRegistrarLista($request, $data);
            $respuesta = true;
            $mensaje = "Se ha registrado un Gestor exitosamente";
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function GestorEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Gestor::GestorEditar($request);
            GestorProducto::GestorProductoRegistrar($request);
            GestorRuta::GestorRutaRegistrar($request);
            GestorSupervisor::GestorSupervisorRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha editado el Gestor exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function GestorBloquearJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Gestor::GestorBloquear($request);
            $respuesta = true;
            $mensaje = "Se ha bloqueado el Gestor exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function GestorRestablecerJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Gestor::GestorRestablecer($request);
            $respuesta = true;
            $mensaje = "Se ha restablecido el Gestor exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function GestorImportarDataJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        $data = "";
        try {
            $extension = $request->file('gestorExcel')->extension();
            if ($extension == "txt") {
                $data = Gestor::GestorImportarData($request);
                if ($data->respuesta) {
                    $respuesta = true;
                    $mensaje = "Se ha importado la información exitosamente";
                } else {
                    $mensaje = $data->mensaje;
                }
            } else {
                $mensaje = "El formato del archivo no es CSV";
            }
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function GestorEliminarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Gestor::GestorEliminar($request);
            $respuesta = true;
            $mensaje = "Se ha eliminado los Gestores exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
