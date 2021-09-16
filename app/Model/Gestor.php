<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Gestor extends Model
{
    protected $table = "gestor";
    protected $primaryKey = "idGestor";
    protected $fillable = [
        'idCeo',
        'idMesa',
        'codigoGestor',
        'nombre',
        'telefono',
        'nroDocumento',
        'estado',
    ];
    public $timestamps = false;

    public static function GestorListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            g.idGestor,
            g.idCeo,
            ct.nombreCeo,
            g.codigoGestor,
            g.nombre,
            g.telefono,
            g.nroDocumento,
            g.estado
            FROM gestor AS g
        INNER JOIN centrooperativo AS ct ON ct.idCeo = g.idCeo
        WHERE ct.idCeo = $idCeo"));
    }
    public static function GestorRegistrar(Request $request)
    {
        $data = new Gestor();
        $data->idCeo = $request->input('idCeo');
        $data->codigoGestor = $request->input('codigoGestor');
        $data->nombre = $request->input('nombre');
        $data->telefono = $request->input('telefono');
        $data->nroDocumento = $request->input('nroDocumento');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function GestorEditar(Request $request)
    {
        $data = Gestor::findOrfail($request->input('idGestor'));
        $data->codigoGestor = $request->input('codigoGestor');
        $data->nombre = $request->input('nombre');
        $data->telefono = $request->input('telefono');
        $data->nroDocumento = $request->input('nroDocumento');
        $data->save();
        return $data;
    }
    public static function GestorBloquear(Request $request)
    {
        $data = Gestor::findOrfail($request->input('idGestor'));
        $data->estado = 0;
        $data->save();
        return $data;
    }
    public static function GestorRestablecer(Request $request)
    {
        $data = Gestor::findOrfail($request->input('idGestor'));
        $data->estado = 1;
        $data->save();
        return $data;
    }
}
