<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = "mesa";
    protected $primaryKey = "idMesa";
    protected $fillable = [
        'idCeo',
        'nombre',
        'estado',
    ];
    public $timestamps = false;
    public function CentroOperativo()
    {
        return $this->belongsTo(CentroOperativo::class, 'idCeo');
    }
    public static function MesaListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            m.idMesa,
            m.nombre,
            m.estado
        FROM mesa as m where m.idCeo = $idCeo"));
    }
    public static function MesaRegistrar(Request $request)
    {
        $data = new Mesa();
        $data->idCeo = $request->input('idCeo');
        $data->nombre = $request->input('nombre');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function MesaEditar(Request $request)
    {
        $data = Mesa::findOrfail($request->input('idMesa'));
        $data->nombre = $request->input('nombre');
        $data->save();
        return $data;
    }
}
