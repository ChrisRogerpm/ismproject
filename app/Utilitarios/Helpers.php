<?php

use App\Model\RolPermiso;
use Illuminate\Support\Facades\Auth;

function ValidarModuloPermiso($modulo)
{
    $usuario = Auth::user();
    $ListaModulos = RolPermiso::where('idRol', $usuario->idRol)->get();
    if ($usuario->idRol == 1) {
        $respuesta = true;
    } else {
        $ListaModulos = $ListaModulos->map(function ($item) {
            return $item['permisoModulo'];
        })->toArray();
        $respuesta = in_array($modulo, $ListaModulos) == 1 ? true : false;
    }

    return $respuesta;
}
