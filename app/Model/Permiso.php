<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = "permiso";
    protected $primaryKey = "idPermiso";
    protected $fillable = [
        'nombrePermiso',
        'modulo',
        'estado',
    ];
    public $timestamps = false;

    public static function PermisoListar()
    {
        return DB::select(DB::raw("SELECT
        UPPER(p.modulo) AS modulo
        FROM permiso as p
        GROUP BY p.modulo
        "));
    }
    public static function ValidarPermisoRol(Request $request)
    {
        $respuesta = false;
        $usuarioLogeado = Auth::user();
        if ($usuarioLogeado->idRol == 1) {
            $respuesta = true;
        } else {
            $RolPermiso = RolPermiso::where('idRol', $usuarioLogeado->idRol)->get();
            $RolPermiso = $RolPermiso->map(function ($item) {
                return $item->permisoModulo;
            })->toArray();
            $rutaEntrante = Route::current()->uri();
            $data = Permiso::whereIn('modulo', $RolPermiso)->where('nombrePermiso', $rutaEntrante)->first();
            if ($data != null) {
                $respuesta = true;
            }
        }

        return $respuesta;
    }
}
