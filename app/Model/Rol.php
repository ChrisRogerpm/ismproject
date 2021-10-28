<?php

namespace App\Model;

use stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = "rol";
    protected $primaryKey = "idRol";
    protected $fillable = [
        'nombreRol',
        'paginaInicio',
        'estado',
    ];
    public $timestamps = false;

    public static function RolListar()
    {
        return DB::select(DB::raw("SELECT
            r.idRol,
            r.nombreRol,
            p.nombrePermiso as paginaInicio,
            IF(r.estado = 1,'ACTIVO','INACTIVO') AS estado
        FROM rol as r
        LEFT JOIN permiso AS p ON p.idPermiso = r.paginaInicio
        WHERE r.idRol != 1"));
    }

    public static function RolRegistrar(Request $request)
    {
        $data = new Rol();
        $data->nombreRol = $request->input('nombreRol');
        $data->paginaInicio = $request->input('paginaInicio');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function RolEditar(Request $request)
    {
        $data = Rol::findOrfail($request->input('idRol'));
        $data->nombreRol = $request->input('nombreRol');
        $data->paginaInicio = $request->input('paginaInicio');
        $data->save();
        return $data;
    }
    public static function RolValidarUsabilidadRoles(Request $request)
    {
        $ListaRolesUsados = [];
        $ListaRolesEliminar = $request->input('ListaRolesEliminar');
        foreach ($ListaRolesEliminar as $rol) {
            $objUsuario = Usuario::where('idRol', $rol)->first();
            if ($objUsuario != null) {
                $objRol = Rol::findOrfail($rol);
                $ListaRolesUsados[] = $objRol->nombreRol;
            }
        }
        $obj = new stdClass();
        $obj->respuesta = count($ListaRolesUsados) > 0 ? true : false;
        $obj->Lista = $ListaRolesUsados;
        return $obj;
    }
    public static function RolEliminar(Request $request)
    {
        $ListaRolesEliminar = $request->input('ListaRolesEliminar');
        RolPermiso::whereIn('idRol', $ListaRolesEliminar)->delete();
        Rol::whereIn('idRol', $ListaRolesEliminar)->delete();
    }
}
