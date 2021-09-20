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
        $data->estado = 0;
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
    public static function BonificacionListarProcesado(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
        co.nombreCeo,
        l.nombre AS nombreLinea,
        p.marca,
        p.formato,
        p.codigoPadre,
        IF(bd.condicionAt = 1,p.unidadxCaja,p.unidadxPaquete) AS cajaX,
        IF(bd.condicionAt = 1,'CAJA','PAQUETE') AS condicionAt,
        p.sku,
        bd.nroBotellasBonificar,
        CONCAT(
        (SELECT px.marca FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar),
        ' / ',
        (SELECT px.formato FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar)
        ) AS marcaFormatoBonificar,
        (SELECT px.sku FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar) AS saborBonificar,
        CONCAT(DATEDIFF(b.fechaFin, b.fechaInicio),' Días') AS diasBonificar,
        b.fechaInicio,
        b.fechaFin
        FROM bonificaciondetalle AS bd
        INNER JOIN bonificacion AS b ON b.idBonificacion = bd.idBonificacion
        INNER JOIN centrooperativo AS co ON co.idCeo = b.idCeo
        INNER JOIN producto AS p ON p.idProducto = bd.idProducto
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE bd.idBonificacion = (SELECT bf.idBonificacion FROM bonificacion AS bf WHERE bf.estado = 1 ORDER BY bf.idBonificacion LIMIT 1) AND b.idCeo = $idCeo"));
    }
}
