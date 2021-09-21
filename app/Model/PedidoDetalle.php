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
        'idProducto',
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
        p.sku,
        pd.cantidad,
        pd.precio,
        pd.precioDescuento,
        pd.descuento,
        pd.tdocto
        FROM pedidodetalle AS pd
        INNER JOIN producto AS p ON p.idProducto = pd.idProducto
        WHERE pd.idCeo = $idCeo AND pd.nroPedido = '$nroPedido'"));
    }
    public static function PedidoDetalleRegistrar(Request $request)
    {
        $data = new PedidoDetalle();
        $data->idCeo = $request->input('idCeo');
        $data->nroPedido = $request->input('nroPedido');
        $data->fechaVenta = $request->input('fechaVenta');
        $data->fechaMovimiento = $request->input('fechaMovimiento');
        $data->idProducto = $request->input('idProducto');
        $data->cantidad = $request->input('cantidad');
        $data->precio = $request->input('precio');
        $data->precioDescuento = $request->input('precioDescuento');
        $data->descuento = $request->input('descuento');
        $data->tdocto = $request->input('tdocto');
        $data->save();
        return $data;
    }
}
