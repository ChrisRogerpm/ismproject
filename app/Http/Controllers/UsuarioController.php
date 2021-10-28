<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    #region Vista
    public function UsuarioListarVista()
    {
        return view('Usuario.UsuarioListar');
    }
    public function UsuarioRegistrarVista()
    {
        return view('Usuario.UsuarioRegistrar');
    }
    public function UsuarioEditarVista($idUsuario)
    {
        $Usuario = Usuario::findOrfail($idUsuario);
        return view('Usuario.UsuarioEditar', compact('Usuario'));
    }
    #endregion
    #region JSON
    public function UsuarioListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data  = Usuario::UsuarioListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function UsuarioRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            $validarEmail = Usuario::where('email', $request->input('email'))->first();
            if ($validarEmail != null) {
                $mensaje = "El email ingresado ya está siendo usado, intente con uno nuevo";
            } else {
                Usuario::UsuarioRegistrar($request);
                $respuesta = true;
                $mensaje = "Se ha registrado el Usuario exitosamente";
            }
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function UsuarioEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            $validarEmail = Usuario::where('email', $request->input('email'))->first();
            if ($validarEmail != null) {
                if ($validarEmail->idUsuario == $request->input('idUsuario')) {
                    Usuario::UsuarioEditar($request);
                    $respuesta = true;
                    $mensaje = "Se ha editado el Usuario exitosamente";
                } else {
                    $mensaje = "El email ingresado ya está siendo usado, intente con uno nuevo";
                }
            } else {
                Usuario::UsuarioEditar($request);
                $respuesta = true;
                $mensaje = "Se ha editado el Usuario exitosamente";
            }
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function UsuarioBloquearJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Usuario::UsuarioBloquear($request);
            $respuesta = true;
            $mensaje = "Se ha bloqueado al Usuario exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function UsuarioRestablecerJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Usuario::UsuarioRestablecer($request);
            $respuesta = true;
            $mensaje = "Se ha restablecido al Usuario exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
