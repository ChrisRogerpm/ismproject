<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CentroOperativo extends Model
{
    protected $table = 'centrooperativo';
    protected $primaryKey = "idCeo";
    protected $fillable = [
        'nombreCeo',
        'codigoCeo',
        'empresa',
        'lugar',
        'estado',
    ];
    public $timestamps = false;

    public static function CentroOperativoRegistrar(Request $request)
    {
        $data = new CentroOperativo();
        $data->nombreCeo = $request->request->get('nombreCeo');
        $data->codigoCeo = $request->request->get('codigoCeo');
        $data->empresa = $request->request->get('empresa');
        $data->lugar = $request->request->get('lugar');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function CentroOperativoEditar(Request $request)
    {
        $data = CentroOperativo::findOrfail($request->input('idCeo'));
        $data->nombreCeo = $request->request->get('nombreCeo');
        $data->codigoCeo = $request->request->get('codigoCeo');
        $data->empresa = $request->request->get('empresa');
        $data->lugar = $request->request->get('lugar');
        $data->save();
        return $data;
    }
    public static function CentroOperativoListar()
    {
        return DB::select(DB::raw("SELECT
            co.idCeo,
            co.nombreCeo,
            co.codigoCeo,
            co.empresa,
            co.lugar,
            co.estado
        FROM centrooperativo AS co
        ORDER BY co.idCeo DESC"));
    }
    public static function CentroOperativoListarActivos()
    {
        $usuario = Auth::user();
        $whereAdicional = $usuario->idCeo == 0 ? '' : 'AND co.idCeo =' . $usuario->idCeo;
        return DB::select(DB::raw("SELECT
            co.idCeo,
            co.nombreCeo,
            co.codigoCeo,
            co.empresa,
            co.lugar,
            co.estado
        FROM centrooperativo AS co
        WHERE co.estado = 1 $whereAdicional
        ORDER BY co.idCeo DESC"));
    }
    public static function CentroOperativoBloquear(Request $request)
    {
        $data = CentroOperativo::findOrfail($request->input('idCeo'));
        $data->estado = 0;
        $data->save();
    }
    public static function CentroOperativoRestablecer(Request $request)
    {
        $data = CentroOperativo::findOrfail($request->input('idCeo'));
        $data->estado = 1;
        $data->save();
    }
}
