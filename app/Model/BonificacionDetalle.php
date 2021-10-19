<?php

namespace App\Model;

use App\Model\Bonificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class BonificacionDetalle extends Model
{
    protected $table = "bonificaciondetalle";
    protected $primaryKey = "idBonificacionDetalle";
    protected $fillable = [
        'idBonificacion',
        'idProducto',
        'cajaX',
        'condicionAt',
        'nroBotellasBonificar',
        'idProductoBonificar',
    ];
    public $timestamps = false;
    public static function BonificacionDetalleListar(Request $request)
    {
        $idBonificacion = $request->input('idBonificacion');
        return DB::select(DB::raw("SELECT
            bd.idBonificacionDetalle,
            p.idProducto,
            l.nombre AS nombreLinea,
            p.marca,
            p.formato,
            p.codigoPadre,
            IF(bd.condicionAt = 1,p.unidadxCaja,p.unidadxPaquete) AS cajaX,
            bd.condicionAt,
            p.sku,
            bd.nroBotellasBonificar,
            (SELECT px.marca FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar) AS marcaBonificar,
            (SELECT px.formato FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar) AS formatoBonificar,
            CONCAT((SELECT px.marca FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar),'/',(SELECT px.formato FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar)) AS marcaFormatoBonificar,
            bd.idProductoBonificar,
            p.unidadxCaja AS caja,
            p.unidadxPaquete AS paquete,
            0 AS estadoEliminar
        FROM bonificaciondetalle AS bd
        INNER JOIN producto AS p ON p.idProducto = bd.idProducto
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE bd.idBonificacion = $idBonificacion"));
    }
    public static function BonificacionDetalleRegistrarLista(Request $request, Bonificacion $obj)
    {
        $ListaProductosBonificacion = $request->input('ListaProductosBonificacion');
        foreach ($ListaProductosBonificacion as $lista) {
            $data = new BonificacionDetalle();
            $data->idBonificacion = $obj->idBonificacion;
            $data->idProducto = $lista['idProducto'];
            $data->cajaX = $lista['cajaX'];
            $data->condicionAt = $lista['condicionAt'];
            $data->nroBotellasBonificar = $lista['nroBotellasBonificar'];
            $data->idProductoBonificar = $lista['idProductoBonificar'];
            $data->save();
        }
    }
    public static function BonificacionDetalleRegistrar(Request $request)
    {
        $ListaProductosBonificacion = $request->input('ListaProductosBonificacion');
        foreach ($ListaProductosBonificacion as $lista) {
            $obj = BonificacionDetalle::where('idBonificacion', $request->input('idBonificacion'))->where('idProducto', $lista['idProducto'])->first();
            if ($obj == null) {
                $data = new BonificacionDetalle();
                $data->idBonificacion = $request->input('idBonificacion');
                $data->idProducto = $lista['idProducto'];
                $data->cajaX = $lista['cajaX'];
                $data->condicionAt = $lista['condicionAt'];
                $data->nroBotellasBonificar = $lista['nroBotellasBonificar'];
                $data->idProductoBonificar = $lista['idProductoBonificar'];
                $data->save();
            }
        }
    }
    public static function BonificacionDetalleEliminar(Request $request)
    {
        $ListaProductosEliminar = $request->input('ListaProductosEliminar');
        return BonificacionDetalle::whereIn('idProducto', $ListaProductosEliminar)
            ->where('idBonificacion', $request->input('idBonificacion'))
            ->delete();
    }
    public static function BonificacionDetalleEditarLista(Request $request)
    {
        $ListaProductosBonificacion = $request->input('ListaProductosBonificacion');
        foreach ($ListaProductosBonificacion as $lista) {
            $obj = BonificacionDetalle::where('idBonificacion', $request->input('idBonificacion'))->where('idProducto', $lista['idProducto'])->first();
            if ($obj == null) {
                $data = new BonificacionDetalle();
                $data->idBonificacion = $request->input('idBonificacion');
                $data->idProducto = $lista['idProducto'];
                $data->cajaX = $lista['cajaX'];
                $data->condicionAt = $lista['condicionAt'];
                $data->nroBotellasBonificar = $lista['nroBotellasBonificar'];
                $data->idProductoBonificar = $lista['idProductoBonificar'];
                $data->save();
            } else {
                $obj->cajaX = $lista['cajaX'];
                $obj->condicionAt = $lista['condicionAt'];
                $obj->nroBotellasBonificar = $lista['nroBotellasBonificar'];
                $obj->idProductoBonificar = $lista['idProductoBonificar'];
                $obj->save();
            }
        }
    }
}
