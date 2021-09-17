<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Bonificacion extends Model
{
    protected $table = "bonificacion";
    protected $primaryKey = "idBonificacion";
    protected $fillable = [
        'idCeo',
        'fechaInicio',
        'fechaFin',
        'diasBonificar',
        'estado',
    ];
    public $timestamps = false;
    public static function BonificacionListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
        b.idBonificacion,
        b.idCeo,
        b.fechaInicio,
        b.fechaFin,
        b.diasBonificar,
        b.estado,
        IF(b.estado = 1,'ACTIVO','INACTIVO') as estadoNombre
        FROM bonificacion as b
        WHERE b.idCeo = $idCeo"));
    }
    public static function BonificacionRegistrar(Request $request)
    {
        $data = new Bonificacion();
        $data->idCeo = $request->input('idCeo');
        $data->fechaInicio = $request->input('fechaInicio');
        $data->fechaFin = $request->input('fechaFin');
        $data->diasBonificar = $request->input('diasBonificar');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function BonificacionEditar(Request $request)
    {
        $data = Bonificacion::findOrfail($request->input('idBonificacion'));
        $data->fechaInicio = $request->input('fechaInicio');
        $data->fechaFin = $request->input('fechaFin');
        $data->diasBonificar = $request->input('diasBonificar');
        $data->save();
        return $data;
    }
    public static function BonificacionActivar(Request $request)
    {
        $data = Bonificacion::findOrfail($request->input('idBonificacion'));
        $data->estado = 1;
        $data->save();

        DB::table('bonificacion')
            ->where('idBonificacion', '!=', $data->idBonificacion)
            ->where('idCeo', $data->idCeo)
            ->update(['estado' => 0]);
    }
}
