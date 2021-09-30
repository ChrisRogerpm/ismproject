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
}
