<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Linea extends Model
{
    protected $table = "linea";
    protected $primaryKey = "idLinea";
    protected $fillable = [
        'idCeo',
        'nombre',
        'estado',
    ];
    public $timestamps = false;
    public static function LineaListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            l.idLinea,
            l.nombre,
            l.estado
        FROM linea AS l
        WHERE l.idCeo = $idCeo"));
    }
    public static function LineaListarActivos(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            l.idLinea,
            l.nombre,
            l.estado
        FROM linea AS l
        WHERE l.idCeo = $idCeo AND l.estado = 1"));
    }
    public static function LineaRegistrar(Request $request)
    {
        $data = new Linea();
        $data->idCeo = $request->input('idCeo');
        $data->nombre = $request->input('nombre');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function LineaEditar(Request $request)
    {
        $data = Linea::findOrfail($request->input('idLinea'));
        $data->nombre = $request->input('nombre');
        $data->save();
        return $data;
    }
    public static function LineaBloquear(Request $request)
    {
        $data = Linea::findOrfail($request->input('idLinea'));
        $data->estado = 0;
        $data->save();
        return $data;
    }
    public static function LineaRestablecer(Request $request)
    {
        $data = Linea::findOrfail($request->input('idLinea'));
        $data->estado = 1;
        $data->save();
        return $data;
    }
}
