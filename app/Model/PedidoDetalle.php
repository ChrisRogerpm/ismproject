<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    protected $table = "pedidodetalle";
    protected $primaryKey = "idPedidoDetalle";
    protected $fillable = [
        'idCeo',
        'nroPedido',
        'fechaVenta',
        'fechaMovimiento',
        'sku',
        'cantidad',
        'precio',
        'precioDescuento',
        'descuento',
        'tdocto',
    ];
    public $timestamps = false;
    public static function PedidoDetalleListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        $nroPedido = $request->input('nroPedido');
        return DB::select(DB::raw("SELECT
        pd.nroPedido,
        pd.fechaVenta,
        pd.fechaMovimiento,
        pd.sku,
        pd.cantidad,
        pd.precio,
        pd.precioDescuento,
        pd.descuento,
        pd.tdocto
        FROM pedidodetalle AS pd
        WHERE pd.idCeo = $idCeo AND pd.nroPedido = '$nroPedido'"));
    }
    public static function PedidoDetalleListarProductosCodigoPadre(Request $request)
    {
        $idGestor = $request->input('idGestor');
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');
        $idCeo = $request->input('idCeo');
        $data = collect(DB::select(DB::raw("SELECT
        pd.nroPedido,
        (SELECT p.codigoPadre FROM producto AS p WHERE p.sku = pd.sku AND p.idCeo = $idCeo) AS codigoPadre,
        pd.cantidad
        FROM pedidodetalle AS pd
        WHERE
        pd.nroPedido IN (SELECT p.nroPedido FROM pedido AS p WHERE p.idGestor = '$idGestor' AND p.idCeo = $idCeo)
        AND pd.fechaVenta BETWEEN '$fechaInicio' AND '$fechaFin'")));
        $lista = [];
        $codigoPadres = $data->groupBy('codigoPadre')->keys()->toArray();
        foreach ($codigoPadres as $codigo) {
            $lista[] = [
                'codigoPadre' => $codigo,
                'marca' => Producto::where('codigoPadre', $codigo)->where('idCeo', $idCeo)->first()->marca,
                'cantidad' => $data->where('codigoPadre', $codigo)->sum('cantidad')
            ];
        }
        return $lista;
    }
    public static function PedidoDetalleListarProductosGestor(Request $request)
    {
        $idCeo = $request->input('idCeo');
        $idGestor = $request->input('idGestor');
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');
        $ListaProductosVendidos = self::PedidoDetalleListarProductosCodigoPadre(new Request([
            'idGestor' => $idGestor,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'idCeo' => $idCeo,
        ]));
        $Comision = Comision::where('estado', 1)->where('idCeo', $request->input('idCeo'))->first();
        $ListaComisiones = [];
        if ($Comision != null) {
            $ListaComisiones = ComisionDetalle::where('idComision', $Comision->idComision)->get();
        }
        $ListaFinal = [];
        foreach ($ListaProductosVendidos as $obj) {
            $objComision = $ListaComisiones->where('codigoPadre', $obj['codigoPadre'])->first();
            $contador = 0.00;
            if ($objComision != null) {
                $calculo = round($obj['cantidad'] / $objComision->cantidadValor);
                if ($calculo > 0) {
                    $contador += $calculo * $objComision->comisionDistribuidor;
                }
            }
            $ListaFinal[] = [
                'codigoPadre' => $obj['codigoPadre'],
                'marca' => $obj['marca'],
                'cantidad' => $obj['cantidad'],
                'montoComision' => number_format(round($contador, 3), 2)
            ];
        }

        return $ListaFinal;
    }
}
