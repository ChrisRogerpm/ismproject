<?php

namespace App\Model;

use App\Model\Gestor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GestorRuta extends Model
{
    protected $table = "gestorruta";
    protected $primaryKey = "idGestorRuta";
    protected $fillable = [
        'idGestor',
        'idRuta',
    ];
    public $timestamps = false;
    public static function GestorRutaRegistrarLista(Request $request, Gestor $obj)
    {
        $ListaRutasRegistrados = $request->input('ListaRutasRegistrados');
        foreach ($ListaRutasRegistrados as $lista) {
            $data = new GestorRuta();
            $data->idGestor = $obj->idGestor;
            $data->idRuta = $lista['idRuta'];
            $data->save();
        }
    }
    public static function GestorRutaListar(Request $request)
    {
        $idGestor = $request->input('idGestor');
        return DB::select(DB::raw("SELECT
            gr.idGestorRuta,
            gr.idGestor,
            r.descripcion
        FROM gestorruta AS gr
        INNER JOIN ruta AS r ON r.idRuta = gr.idRuta
        WHERE gr.idGestor = $idGestor"));
    }
    public static function GestorRutaRegistrar(Request $request)
    {
        $ListaRutasRegistrados = $request->input('ListaRutasRegistrados');
        foreach ($ListaRutasRegistrados as $lista) {
            $obj = GestorRuta::where('idRuta', $lista['idRuta'])->where('idGestor', $request->input('idGestor'))->first();
            if ($obj == null) {
                $data = new GestorRuta();
                $data->idGestor = $request->input('idGestor');
                $data->idRuta = $lista['idRuta'];
                $data->save();
            }
        }
    }
    public static function GestorRutaEliminar(Request $request)
    {
        $data = GestorRuta::findOrfail($request->input('idGestorRuta'));
        $data->delete();
    }
}
