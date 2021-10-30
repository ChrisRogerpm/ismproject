<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Rol;
use App\Model\Permiso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class RolPermisoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $respuesta = Permiso::ValidarPermisoRol($request);
        if (!$respuesta) {
            if (Route::current()->methods[0] == "GET") {
                $rol = Rol::findOrfail(Auth::user()->idRol);
                $objPermiso = Permiso::where('modulo', $rol->paginaInicio)->first();
                $paginaInicioRedireccion = $objPermiso->nombrePermiso;
                $ruta = Route::current()->uri();
                $moduloPermiso = Permiso::where('nombrePermiso', $ruta)->first();
                return redirect($paginaInicioRedireccion)->with('message', "NO TIENE AUTORIZACIÓN PARA INGRESAR AL MODULO $moduloPermiso->modulo DEL SISTEMA!");
            } else {

                $rol = Rol::findOrfail(Auth::user()->idRol);
                $objPermiso = Permiso::where('modulo', $rol->paginaInicio)->first();
                $paginaInicioRedireccion = $objPermiso->nombrePermiso;
                $ruta = Route::current()->uri();
                $moduloPermiso = Permiso::where('nombrePermiso', $ruta)->first();

                return response()->json(
                    [
                        'mensaje' => "NO TIENE AUTORIZACIÓN PARA HACER USO DEL MODULO $moduloPermiso->modulo DEL SISTEMA!.",
                        'respuesta' => false
                    ],
                    401
                );
            }
        } else {
            return $next($request);
        }
    }
}
