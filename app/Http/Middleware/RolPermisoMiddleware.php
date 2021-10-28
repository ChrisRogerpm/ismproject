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
                return redirect($paginaInicioRedireccion)->with('message', 'NO TIENE AUTORIZACIÓN PARA INGRESAR A ESE SECTOR DEL SISTEMA!');
            } else {
                return response()->json(
                    [
                        'mensaje' => 'NO TIENE PERMISO PARA USAR ESTE MÓDULO.',
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
