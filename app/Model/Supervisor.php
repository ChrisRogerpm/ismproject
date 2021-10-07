<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $table = "supervisor";
    protected $primaryKey = "idSupervisor";
    protected $fillable = [
        'idCeo',
        'nombre',
        'estado',
    ];
    public $timestamps = false;
    public static function SupervisorListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            s.idSupervisor,
            s.nombre,
            s.estado
        FROM supervisor AS s
        WHERE s.idCeo = $idCeo"));
    }
    public static function SupervisorRegistrar(Request $request)
    {
        $data = new Supervisor();
        $data->idCeo = $request->input('idCeo');
        $data->nombre = $request->input('nombre');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function SupervisorEditar(Request $request)
    {
        $data = Supervisor::findOrfail($request->input('idSupervisor'));
        $data->nombre = $request->input('nombre');
        $data->save();
        return $data;
    }
}
