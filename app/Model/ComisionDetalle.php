<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ComisionDetalle extends Model
{
    protected $table = "comisiondetalle";
    protected $primaryKey = "idComisionDetalle";
    protected $fillable = [
        'idComision',
        'idProducto',
        'codigoPadre',
        'condicion',
        'cantidadValor',
        'comisionPtoVenta',
        'comisionDistribuidor',
    ];
    public $timestamps = false;
    public static function ComisionDetalleRegistrarLista(Request $request, Comision $obj)
    {
        $ListaProductosComision = $request->input('ListaProductosComision');
        foreach ($ListaProductosComision as $lista) {
            $data = new ComisionDetalle();
            $data->idComision = $obj->idComision;
            $data->idProducto = $lista['idProducto'];
            $data->codigoPadre = $lista['codigoPadre'];
            $data->condicion = $lista['condicion'];
            $data->cantidadValor = $lista['cantidadValor'];
            $data->comisionPtoVenta = $lista['comisionPtoVenta'];
            $data->comisionDistribuidor = $lista['comisionDistribuidor'];
            $data->save();
        }
    }
    public static function ComisionDetalleRegistrar(Request $request)
    {
        $ListaProductosComision = $request->input('ListaProductosComision');
        foreach ($ListaProductosComision as $lista) {
            $obj = ComisionDetalle::where('idComision', $request->input('idComision'))
                ->where('codigoPadre', $lista['codigoPadre'])
                ->first();
            if ($obj == null) {
                $data = new ComisionDetalle();
                $data->idComision = $obj->idComision;
                $data->idProducto = $lista['idProducto'];
                $data->codigoPadre = $lista['codigoPadre'];
                $data->condicion = $lista['condicion'];
                $data->cantidadValor = $lista['cantidadValor'];
                $data->comisionPtoVenta = $lista['comisionPtoVenta'];
                $data->comisionDistribuidor = $lista['comisionDistribuidor'];
                $data->save();
            }
        }
    }
    public static function ComisionDetalleEditarLista(Request $request)
    {
        $ListaProductosComision = $request->input('ListaProductosComision');
        foreach ($ListaProductosComision as $lista) {
            $data = ComisionDetalle::findOrfail($lista['idComisionDetalle']);
            $data->condicion = $lista['condicion'];
            $data->cantidadValor = $lista['cantidadValor'];
            $data->comisionPtoVenta = $lista['comisionPtoVenta'];
            $data->comisionDistribuidor = $lista['comisionDistribuidor'];
            $data->save();
        }
    }
    public static function ComisionDetalleListar(Request $request)
    {
        $idComision = $request->input('idComision');
        return DB::select(DB::raw("SELECT
            cd.idComisionDetalle,
            cd.idProducto,
            cd.codigoPadre,
            (SELECT p.nombre FROM producto AS p WHERE p.idProducto = cd.idProducto) AS nombreProducto,
            cd.condicion,
            cd.cantidadValor,
            cd.comisionPtoVenta,
            cd.comisionDistribuidor
        FROM comisiondetalle AS cd
        WHERE cd.idComision = $idComision"));
    }
    public static function ComisionDetalleEliminar(Request $request)
    {
        $ListaProductosEliminar = $request->input('ListaProductosEliminar');
        ComisionDetalle::whereIn('idComisionDetalle', $ListaProductosEliminar)->deleted();
    }
}
