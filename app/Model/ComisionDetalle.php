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
            $data = ComisionDetalle::where('codigoPadre', $lista['codigoPadre'])->where('idComision', $request->input('idComision'))->first();
            if ($data != null) {
                $data->condicion = $lista['condicion'];
                $data->cantidadValor = $lista['cantidadValor'];
                $data->comisionPtoVenta = $lista['comisionPtoVenta'];
                $data->comisionDistribuidor = $lista['comisionDistribuidor'];
                $data->save();
            } else {
                $obj = new ComisionDetalle();
                $obj->idComision = $request->input('idComision');
                $obj->codigoPadre = $lista['codigoPadre'];
                $obj->condicion = $lista['condicion'];
                $obj->cantidadValor = $lista['cantidadValor'];
                $obj->comisionPtoVenta = $lista['comisionPtoVenta'];
                $obj->comisionDistribuidor = $lista['comisionDistribuidor'];
                $obj->save();
            }
        }
    }
    public static function ComisionDetalleListar(Request $request)
    {
        $idComision = $request->input('idComision');
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            cd.idComisionDetalle,
            cd.codigoPadre,
            IFNULL(CONCAT(
            (SELECT px.marca FROM producto AS px WHERE px.codigoPadre = cd.codigoPadre AND px.idCeo = $idCeo ORDER BY px.marca DESC LIMIT 1),
            ' ',
            (SELECT px.formato FROM producto AS px WHERE px.codigoPadre = cd.codigoPadre AND px.idCeo = $idCeo ORDER BY px.formato DESC LIMIT 1),
            ' ',
            (SELECT px.sabor FROM producto AS px WHERE px.codigoPadre = cd.codigoPadre AND px.idCeo = $idCeo ORDER BY px.sabor DESC LIMIT 1)
            ),'') AS nombreProducto,
            cd.condicion,
            cd.cantidadValor,
            cd.comisionPtoVenta,
            cd.comisionDistribuidor,
            0 as estadoEliminar
        FROM comisiondetalle AS cd
        WHERE cd.idComision = $idComision"));
    }
    public static function ComisionDetalleListarExcel(Request $request)
    {
        $idComision = $request->input('idComision');
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            cd.codigoPadre,
            IFNULL(CONCAT(
            (SELECT px.marca FROM producto AS px WHERE px.codigoPadre = cd.codigoPadre AND px.idCeo = $idCeo ORDER BY px.marca DESC LIMIT 1),
            ' ',
            (SELECT px.formato FROM producto AS px WHERE px.codigoPadre = cd.codigoPadre AND px.idCeo = $idCeo ORDER BY px.formato DESC LIMIT 1),
            ' ',
            (SELECT px.sabor FROM producto AS px WHERE px.codigoPadre = cd.codigoPadre AND px.idCeo = $idCeo ORDER BY px.sabor DESC LIMIT 1)
            ),'') AS nombreProducto,
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
        ComisionDetalle::whereIn('codigoPadre', $ListaProductosEliminar)->where('idComision', $request->input('idComision'))->delete();
    }
}
