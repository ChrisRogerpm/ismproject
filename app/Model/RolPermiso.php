<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class RolPermiso extends Model
{
    protected $table = "rolpermiso";
    protected $primaryKey = "idRolPermiso";
    protected $fillable = [
        'idRol',
        'permisoModulo'
    ];
    public $timestamps = false;

    public static function RolPermisoRegistrar(Request $request)
    {
        $data = new RolPermiso();
        $data->idRol = $request->input('idRol');
        $data->permisoModulo = $request->input('permisoModulo');
        $data->save();
        return $data;
    }
    public static function RolPermisoEditar(Request $request)
    {
        $data = RolPermiso::findOrfail($request->input('idRolPermiso'));
        $data->permisoModulo = $request->input('permisoModulo');
        $data->save();
        return $data;
    }
}
