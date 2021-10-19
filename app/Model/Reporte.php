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
            pd.sku,
            (SELECT p.sku FROM producto AS p WHERE p.sku = pd.sku AND p.idCeo = $idCeo) AS skuProducto,
            (SELECT p.nombre FROM producto AS p WHERE p.sku = pd.sku AND p.idCeo = $idCeo) AS nombreProducto,
            pd.precio,
            ROUND((SELECT SUM(pdx.cantidad) FROM pedidodetalle AS pdx WHERE pdx.sku = pd.sku AND pdx.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pdx.idCeo = $idCeo),2) AS cantidad,
            (SELECT p.unidadxPaquete FROM producto AS p WHERE p.sku = pd.sku AND p.idCeo = $idCeo) as unidadPaquete,
            ROUND(
                ((ROUND((SELECT SUM(pdx.cantidad) FROM pedidodetalle AS pdx WHERE pdx.sku = pd.sku AND pdx.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pdx.idCeo = $idCeo),2))
                /
                ((SELECT p.unidadxPaquete FROM producto AS p WHERE p.idCeo = $idCeo AND p.sku = pd.sku))
            ),3) AS cantidadPaquetes,
            ROUND((pd.precio * (SELECT SUM(pdx.cantidad) FROM pedidodetalle AS pdx WHERE pdx.sku = pd.sku AND pdx.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pdx.idCeo = $idCeo)),3) as total
        FROM pedidodetalle AS pd
        WHERE pd.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pd.idCeo = $idCeo
        GROUP BY pd.sku,pd.precio
        ORDER BY (SELECT SUM(pdx.cantidad) FROM pedidodetalle AS pdx WHERE pdx.sku = pd.sku AND pdx.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pdx.idCeo = $idCeo) DESC
        "));
    }
    public static function ReporteNroPedidoListar(Request $request)
    {
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');
        $idCeo = $request->input('idCeo');
        $data = DB::select(DB::raw("SELECT
        pd.nroPedido,
        ROUND ((SELECT SUM(pdx.cantidad * pdx.precio) AS TotalPedido FROM pedidodetalle AS pdx WHERE pdx.nroPedido = pd.nroPedido AND pdx.idCeo = pd.idCeo),3) TotalPedido
        FROM pedidodetalle AS pd
        WHERE pd.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin' AND pd.idCeo = $idCeo
        GROUP BY pd.nroPedido,pd.idCeo
        ORDER BY (SELECT SUM(pdx.cantidad * pdx.precio) AS TotalPedido FROM pedidodetalle AS pdx WHERE pdx.nroPedido = pd.nroPedido AND pdx.idCeo = pd.idCeo) DESC
        "));
        foreach ($data as $d) {
            $subData = collect(DB::select(DB::raw("SELECT (SELECT p.marca FROM producto AS p WHERE p.sku = pdx.sku AND p.idCeo = $idCeo) AS marca FROM pedidodetalle AS pdx WHERE pdx.nroPedido = '$d->nroPedido' AND pdx.idCeo = $idCeo")))->groupBy('marca')->keys()->toArray();
            $d->productosInvolucrados = implode(" / ", $subData);
        }
        return $data;
    }
    public static function ReporteComisionesGestores(Request $request)
    {
        $idCeo = $request->input('idCeo');
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');
        $ListaGestores = Gestor::GestorListar($request);
        $Comision = Comision::where('estado', 1)->where('idCeo', $idCeo)->first();
        $ListaComisiones = collect();
        if ($Comision != null) {
            $ListaComisiones = ComisionDetalle::where('idComision', $Comision->idComision)->get();
        }
        foreach ($ListaGestores as $gestor) {
            $ListaSkuCodigoPadre = PedidoDetalle::PedidoDetalleListarProductosCodigoPadre(new Request([
                'idGestor' => $gestor->codigoGestor,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
                'idCeo' => $idCeo,
            ]));
            $contador = 0.00;
            foreach ($ListaSkuCodigoPadre as $obj) {
                $objComision = $ListaComisiones->where('codigoPadre', $obj['codigoPadre'])->first();
                if ($objComision != null) {
                    $calculo = round($obj['cantidad'] / $objComision->cantidadValor);
                    if ($calculo > 0) {
                        $contador += $calculo * $objComision->comisionDistribuidor;
                    }
                }
            }
            $gestor->montoComision = number_format(round($contador, 3), 2);
        }
        return $ListaGestores;
    }
    public static function ReporteGestoresBonificacionCentroOperativo(Request $request)
    {
        $GestoresVigentes = count(Gestor::where('idCeo', $request->input('idCeo'))->where('estado', 1)->get());
        $BonificacionVigente = Bonificacion::where('idCeo', $request->input('idCeo'))->where('estado', 1)->first();
        if ($BonificacionVigente != null) {
            $BonificacionVigente = $BonificacionVigente->fechaInicio . ' / ' . $BonificacionVigente->fechaFin . ' / ' . $BonificacionVigente->diasBonificar . ' DIA(S)';
        } else {
            $BonificacionVigente = "--";
        }
        return [
            'GestoresVigentes' => $GestoresVigentes,
            'BonificacionVigente' => $BonificacionVigente,
        ];
    }
}
