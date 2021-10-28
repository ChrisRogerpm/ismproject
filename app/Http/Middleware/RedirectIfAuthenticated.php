<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Rol;
use App\Model\Permiso;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $usuario = Auth::user();
            $paginaInicioRedireccion = "";
            if ($usuario->idRol == 1) {
                $paginaInicioRedireccion = "Pedido";
            } else {
                $rol = Rol::findOrfail($usuario->idRol);
                $objPermiso = Permiso::where('modulo', $rol->paginaInicio)->first();
                $paginaInicioRedireccion = $objPermiso->nombrePermiso;
            }
            return redirect($paginaInicioRedireccion);
        }

        return $next($request);
    }
}
