<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Cliente;
use Illuminate\Http\Request;
use App\Model\CentroOperativo;

class ClienteController extends Controller
{
    #region Vista
    public function ClienteListarVista()
    {
        return view('Cliente.ClienteListar');
    }
    public function ClienteRegistrarVista($idCeo)
    {
        $nombreCeo = CentroOperativo::findOrfail($idCeo)->nombreCeo;
        return view('Cliente.ClienteRegistrar', compact('nombreCeo', 'idCeo'));
    }
    public function ClienteEditarVista($idCliente)
    {
        $Cliente = Cliente::findOrfail($idCliente);
        $nombreCeo = CentroOperativo::findOrfail($Cliente->idCeo)->nombreCeo;
        $idCeo = $Cliente->idCeo;
        return view('Cliente.ClienteEditar', compact('Cliente', 'nombreCeo', 'idCeo'));
    }
    #endregion
    #region JSON
    public function ClienteListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Cliente::ClienteListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function ClienteRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Cliente::ClienteRegistrar($request);
            $respuesta = true;
            $mensaje = "Se ha registrado un Cliente exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ClienteEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Cliente::ClienteEditar($request);
            $respuesta = true;
            $mensaje = "Se ha editado el Cliente exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ClienteBloquearJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Cliente::ClienteBloquear($request);
            $respuesta = true;
            $mensaje = "Se ha bloqueado el Cliente exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ClienteRestablecerJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Cliente::ClienteRestablecer($request);
            $respuesta = true;
            $mensaje = "Se ha restablecido el Cliente exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function ClienteImportarDataJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            $extension = $request->file('clienteExcel')->extension();
            if ($extension == "txt") {
                Cliente::ClienteImportarData($request);
                $respuesta = true;
                $mensaje = "Se ha importado los clientes exitosamente";
            } else {
                $mensaje = "El formato del archivo no es CSV";
            }
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
