<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MesaRuta extends Model
{
    protected $table = "mesaruta";
    protected $primaryKey = "idMesaRuta";
    protected $fillable = [
        'idMesa',
        'idRuta',
    ];
    public $timestamps = false;
    public static function MesaRutaListar(Request $request)
    {
        $idMesa = $request->input('idMesa');
        return DB::select(DB::raw("SELECT
            mr.idMesaRuta,
            mr.idRuta,
            r.descripcion
        FROM mesaruta AS mr
        INNER JOIN ruta AS r ON r.idRuta = mr.idRuta
        WHERE mr.idMesa = $idMesa"));
    }
    public static function MesaRutaRegistrar(Request $request)
    {
        $data = new MesaRuta();
        $data->idMesa = $request->input('idMesa');
        $data->idRuta = $request->input('idRuta');
        $data->save();
        return $data;
    }
    public static function MesaRutaEliminar(Request $request)
    {
        $data = MesaRuta::findOrfail($request->input('idMesaRuta'));
        $data->delete();
        return $data;
    }
}
