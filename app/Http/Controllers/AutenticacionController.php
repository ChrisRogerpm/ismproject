<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Rol;
use App\Model\Permiso;
use App\Model\Usuario;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AutenticacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['only' => ['LoginVista']]);
    }
    public function LoginVista()
    {
        return view('Autenticacion.Login');
    }
    public function CambiarContraseniaVista()
    {
        return view('Perfil.CambiarContrasenia');
    }
    public function PerfilCambiarContraseniaJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            $usuario = Auth::user();
            if ($usuario != null) {
                $password = $request->input('password');
                $NuevaContrasenia = $request->input('NuevaContrasenia');
                $VerificarContrasenia = $request->input('VerificarContrasenia');
                $usuario_data = Usuario::findorfail($usuario->idUsuario);
                if ($usuario_data != null) {
                    if (Hash::check($password, $usuario_data->password)) {
                        if ($NuevaContrasenia == $VerificarContrasenia) {
                            $request->request->add(['idUsuario' => $usuario->idUsuario]);
                            Usuario::UsuarioPerfilCambiarContrasenia($request);
                            $respuesta = true;
                            $mensaje = "Se ha cambiado la contraseña exitosamente";
                        } else {
                            $mensaje = "La nueva contraseña no coincide";
                        }
                    } else {
                        $mensaje = 'La contraseña anterior ingresada es erronea';
                    }
                }
            } else {
                $mensaje = "Se ha producido un error,cierre sesion y vuelva a ingresar.";
            }
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }

    public function ValidarLoginJson(Request $request)
    {
        $redirect = "";
        $respuesta = false;
        $email = $request->input('email');
        $password = $request->input('password');
        $usuario_data = Usuario::where('email', $email)->first();
        $token = "";
        if ($usuario_data != null) {
            if (Hash::check($password, $usuario_data->password)) {
                if ($usuario_data->estado != 1) {
                    $mensaje = "El usuario " . $usuario_data->nombre . ' ' . $usuario_data->apellido . ' ha sido bloqueado';
                } else {
                    $credenciales = $request->only('email', 'password');
                    if (!$token = JWTAuth::attempt($credenciales)) {
                        return response()->json(['error' => 'credenciales invalidas'], 400);
                    } else {
                        Auth::attempt($credenciales);
                        $respuesta = true;
                        $mensaje = 'Bienvenido ' . $usuario_data->nombre . ' ' . $usuario_data->apellido;
                    }
                }
            } else {
                $mensaje = 'La contraseña ingresada es erronea';
            }
        } else {
            $mensaje = 'El usuario ingresado no existe en nuestros registros';
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje, 'data' => [
            'redirect' => $redirect,
            'token' => $token,
            'user' => $usuario_data
        ]]);
    }
    public function CerrarSesionJson(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/');
    }
}
