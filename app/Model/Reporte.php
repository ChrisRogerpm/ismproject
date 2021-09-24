<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    //
    public static function ReporteProductoListar(Request $request)
    {
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            pd.idProducto,
            (SELECT p.sku FROM producto AS p WHERE p.idProducto = pd.idProducto AND p.idCeo = $idCeo) AS skuProducto,
            (SELECT p.nombre FROM producto AS p WHERE p.idProducto = pd.idProducto AND p.idCeo = $idCeo) AS nombreProducto,
            pd.precio,
            (SELECT SUM(pdx.cantidad) FROM pedidodetalle AS pdx WHERE pdx.idProducto = pd.idProducto AND pdx.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pdx.idCeo = $idCeo) AS cantidad,
            ROUND((pd.precio * (SELECT SUM(pdx.cantidad) FROM pedidodetalle AS pdx WHERE pdx.idProducto = pd.idProducto AND pdx.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pdx.idCeo = $idCeo)),3) as total
        FROM pedidodetalle AS pd
        WHERE pd.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pd.idCeo = $idCeo
        GROUP BY pd.idProducto,pd.precio
        ORDER BY (SELECT SUM(pdx.cantidad) FROM pedidodetalle AS pdx WHERE pdx.idProducto = pd.idProducto AND pdx.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pdx.idCeo = $idCeo) DESC
        "));
    }
    public static function ReporteNroPedidoListar(Request $request)
    {
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
        pd.nroPedido,
        (SELECT GROUP_CONCAT((SELECT p.marca FROM producto AS p WHERE p.idProducto = pdx.idProducto),' ',(SELECT p.sabor FROM producto AS p WHERE p.idProducto = pdx.idProducto) SEPARATOR ' / ') AS nombreProducto FROM pedidodetalle AS pdx WHERE pdx.nroPedido = pd.nroPedido AND pdx.idCeo = pd.idCeo) AS productosInvolucrados,
        ROUND ((SELECT SUM(pdx.cantidad * pdx.precio) AS TotalPedido FROM pedidodetalle AS pdx WHERE pdx.nroPedido = pd.nroPedido AND pdx.idCeo = pd.idCeo),3) TotalPedido
        FROM pedidodetalle AS pd
        WHERE pd.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pd.idCeo = $idCeo
        GROUP BY pd.nroPedido,pd.idCeo
        ORDER BY (SELECT SUM(pdx.cantidad * pdx.precio) AS TotalPedido FROM pedidodetalle AS pdx WHERE pdx.nroPedido = pd.nroPedido AND pdx.idCeo = pd.idCeo) DESC
        "));
    }
}
