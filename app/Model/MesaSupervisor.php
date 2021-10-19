<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MesaSupervisor extends Model
{
    protected $table = "mesasupervisor";
    protected $primaryKey = "idMesaSupervisor";
    protected $fillable = [
        'idMesa',
        'idSupervisor',
    ];
    public $timestamps = false;
    public static function MesaSupervisorListar(Request $request)
    {
        $idMesa = $request->input('idMesa');
        return DB::select(DB::raw("SELECT
            ms.idMesaSupervisor,
            ms.idSupervisor,
            s.nombre
        FROM mesasupervisor AS ms
        INNER JOIN supervisor AS s ON s.idSupervisor = ms.idSupervisor
        WHERE ms.idMesa = $idMesa"));
    }
    public static function MesaSupervisorRegistrar(Request $request)
    {
        $ListaSupervisorsRegistrados = $request->input('ListaSupervisorsRegistrados');
        foreach ($ListaSupervisorsRegistrados as $lista) {
            $obj = MesaSupervisor::where('idSupervisor', $lista['idSupervisor'])
                ->where('idMesa', $request->input('idMesa'))->first();
            if ($obj == null) {
                $data = new MesaSupervisor();
                $data->idMesa = $request->input('idMesa');
                $data->idSupervisor = $lista['idSupervisor'];
                $data->save();
            }
        }
    }
    public static function MesaSupervisorEliminar(Request $request)
    {
        $data = MesaSupervisor::findOrfail($request->input('idMesaSupervisor'));
        $data->delete();
        return $data;
    }
}
