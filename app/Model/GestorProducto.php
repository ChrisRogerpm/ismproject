<?php

namespace App\Model;

use App\Model\Gestor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GestorProducto extends Model
{
    protected $table = "gestorproducto";
    protected $primaryKey = "idGestorProducto";
    protected $fillable = [
        'idGestor',
        'idProducto',
    ];
    public $timestamps = false;
    public function Producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto');
    }
    public static function GestorProductoRegistrarLista(Request $request, Gestor $obj)
    {
        $ListaProductosRegistrados = $request->input('ListaProductosRegistrados');
        foreach ($ListaProductosRegistrados as $lista) {
            $data = new GestorProducto();
            $data->idGestor = $obj->idGestor;
            $data->idProducto = $lista['idProducto'];
            $data->save();
        }
    }
    public static function GestorProductoRegistrar(Request $request)
    {
        $ListaProductosRegistrados = $request->input('ListaProductosRegistrados');
        foreach ($ListaProductosRegistrados as $lista) {
            $obj = GestorProducto::where('idProducto', $lista['idProducto'])->where('idGestor', $request->input('idGestor'))->first();
            if ($obj == null) {
                $data = new GestorProducto();
                $data->idGestor = $request->input('idGestor');
                $data->idProducto = $lista['idProducto'];
                $data->save();
            }
        }
    }
    public static function GestorProductoListar(Request $request)
    {
        $idGestor = $request->input('idGestor');
        return DB::select(DB::raw("SELECT
            gp.idGestorProducto,
            gp.idGestor,
            p.idProducto,
            l.nombre AS nombreLinea,
            p.sku,
            p.nombre AS nombreProducto,
            p.marca,
            p.formato,
            p.sabor,
            p.unidadxCaja AS caja,
            p.unidadxPaquete AS paquete,
            p.cajaxpaquete,
            p.codigoPadre,
            p.codigoHijo,
            p.estado
        FROM gestorproducto AS gp
        INNER JOIN producto AS p ON p.idProducto = gp.idProducto
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE gp.idGestor = $idGestor"));
    }
    public static function GestorProductoEliminar(Request $request)
    {
        $ListaProductosEliminar = $request->input("ListaProductosEliminar");
        foreach ($ListaProductosEliminar as $lista) {
            $data = GestorProducto::findOrfail($lista);
            $data->delete();
        }
    }
    public static function GestorProductoMarcasConcatenadas($idGestor)
    {
        $data = collect(DB::select(DB::raw("SELECT
            p.marca,
            l.nombre AS nombreLinea
        FROM gestorproducto AS gp
        INNER JOIN producto AS p ON p.idProducto = gp.idProducto
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE gp.idGestor = $idGestor GROUP BY p.marca,l.nombre")));
        $data = $data->map(function ($item, $key) {
            return $item->marca;
        })->toArray();
        $data = implode("-", $data);
        return $data;
    }
    public static function GestorProductoLineasConcatenadas($idGestor)
    {
        $data = collect(DB::select(DB::raw("SELECT
            l.nombre AS nombreLinea
        FROM gestorproducto AS gp
        INNER JOIN producto AS p ON p.idProducto = gp.idProducto
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE gp.idGestor = $idGestor GROUP BY l.nombre")));
        $data = $data->map(function ($item, $key) {
            return $item->nombreLinea;
        })->toArray();
        $data = implode("-", $data);
        return $data;
    }
}
