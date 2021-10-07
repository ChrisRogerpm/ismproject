<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Model\CentroOperativo;

class CentroOperativoController extends Controller
{
    #region Vista
    public function CentroOperativoListarVista()
    {
        return view('CentroOperativo.CentroOperativoListar');
    }
    public function CentroOperativoRegistrarVista()
    {
        return view('CentroOperativo.CentroOperativoRegistrar');
    }
    public function CentroOperativoEditarVista($idCeo)
    {
        $CentroOperativo = CentroOperativo::findOrfail($idCeo);
        return view('CentroOperativo.CentroOperativoEditar', compact('CentroOperativo'));
    }
    #endregion
    #region JSON
    public function CentroOperativoListarJson()
    {
        $data = "";
        $mensaje = "";
        try {
            $data = CentroOperativo::CentroOperativoListar();
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }

    public function CentroOperativoListarActivosJson()
    {
        $data = "";
        $mensaje = "";
        try {
            $data = CentroOperativo::CentroOperativoListarActivos();
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function CentroOperativoRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            CentroOperativo::CentroOperativoRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado un Centro Operativo exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function CentroOperativoEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            CentroOperativo::CentroOperativoEditar($request);
            $respuesta = true;
            $mensaje = "Se ha editado el Centro Operativo exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function CentroOperativoBloquearJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            CentroOperativo::CentroOperativoBloquear($request);
            $respuesta = true;
            $mensaje = "Se ha bloqueado el Centro Operativo exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function CentroOperativoRestablecerJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            CentroOperativo::CentroOperativoRestablecer($request);
            $respuesta = true;
            $mensaje = "Se ha restablecido el Centro Operativo exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
