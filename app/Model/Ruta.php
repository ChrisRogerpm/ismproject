<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = "ruta";
    protected $primaryKey = "idRuta";
    protected $fillable = [
        'idCeo',
        'descripcion',
    ];
    public $timestamps = false;

    public static function RutaListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            r.idRuta,
            r.descripcion
        FROM ruta AS r
        WHERE r.idCeo = $idCeo"));
    }
    public static function RutaRegistrar(Request $request)
    {
        $data = new Ruta();
        $data->idCeo = $request->input('idCeo');
        $data->descripcion = $request->input('descripcion');
        $data->save();
        return $data;
    }
    public static function RutaActualizar(Request $request)
    {
        $rutaVacia = "";
        $clienteRuta = DB::table('cliente')
            ->select('ruta')
            ->where('idCeo', $request->input('idCeo'))
            ->where('ruta', '!=', $rutaVacia)
            ->groupBy('ruta')
            ->get();
        foreach ($clienteRuta as $cr) {
            $obj = Ruta::where('descripcion', $cr->ruta)->where('idCeo', $request->input('idCeo'))->first();
            if ($obj == null) {
                $req = new Request([
                    'idCeo' => $request->input('idCeo'),
                    'descripcion' => $cr->ruta,
                ]);
                Ruta::RutaRegistrar($req);
            }
        }
    }
}
