<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GestorSupervisor extends Model
{
    protected $table = "gestorSupervisor";
    protected $primaryKey = "idGestorSupervisor";
    protected $fillable = [
        'idGestor',
        'idSupervisor',
    ];
    public $timestamps = false;
    public function Supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'idSupervisor');
    }
    public static function GestorSupervisorRegistrarLista(Request $request, Gestor $obj)
    {
        $ListaSupervisorsRegistrados = $request->input('ListaSupervisorsRegistrados');
        foreach ($ListaSupervisorsRegistrados as $lista) {
            $data = new GestorSupervisor();
            $data->idGestor = $obj->idGestor;
            $data->idSupervisor = $lista['idSupervisor'];
            $data->save();
        }
    }
    public static function GestorSupervisorListar(Request $request)
    {
        $idGestor = $request->input('idGestor');
        return DB::select(DB::raw("SELECT
            gs.idGestorSupervisor,
            gs.idGestor,
            s.nombre
        FROM gestorsupervisor AS gs
        INNER JOIN supervisor AS s ON s.idSupervisor = gs.idSupervisor
        WHERE gs.idGestor = $idGestor"));
    }
    public static function GestorSupervisorRegistrar(Request $request)
    {
        $ListaSupervisorsRegistrados = $request->input('ListaSupervisorsRegistrados');
        foreach ($ListaSupervisorsRegistrados as $lista) {
            $obj = GestorSupervisor::where('idSupervisor', $lista['idSupervisor'])->where('idGestor', $request->input('idGestor'))->first();
            if ($obj == null) {
                $data = new GestorSupervisor();
                $data->idGestor = $request->input('idGestor');
                $data->idSupervisor = $lista['idSupervisor'];
                $data->save();
            }
        }
    }
    public static function GestorSupervisorEliminar(Request $request)
    {
        $ListaSupervisorsEliminar = $request->input('ListaSupervisorsEliminar');
        foreach ($ListaSupervisorsEliminar as $lista) {
            $data = GestorSupervisor::findOrfail($lista);
            $data->delete();
        }
    }
    public static function GestorSupervisorSupervisorConcatenado($idGestor)
    {
        $data = collect(DB::select(DB::raw("SELECT
        s.nombre
        FROM gestorsupervisor AS gs
        INNER JOIN supervisor AS s ON s.idSupervisor = gs.idSupervisor
        WHERE gs.idGestor = $idGestor")));
        $data = $data->map(function ($item, $key) {
            return $item->nombre;
        })->toArray();
        $data = implode("-", $data);
        return $data;
    }
}
