<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "producto";
    protected $primaryKey = "idProducto";
    protected $fillable = [
        'idCeo',
        'sku',
        'nombre',
        'marca',
        'formato',
        'sabor',
        'unidadxCaja',
        'unidadxPaquete',
        'cajaxpaquete',
        'codigoPadre',
        'codigoHijo',
        'idLinea',
        'estado',
    ];
    public $timestamps = false;

    public static function ProductoRegistrar(Request $request)
    {
        $data = new Producto();
        $data->idCeo = $request->input('idCeo');
        $data->idLinea = $request->input('idLinea');
        $data->sku = $request->input('sku');
        $data->nombre = $request->input('nombre');
        $data->marca = $request->input('marca');
        $data->sabor = $request->input('sabor');
        $data->formato = $request->input('formato');
        $data->unidadxCaja = $request->input('unidadxCaja');
        $data->unidadxPaquete = $request->input('unidadxPaquete');
        $data->cajaxpaquete = $request->input('cajaxpaquete');
        $data->codigoPadre = $request->input('codigoPadre');
        $data->codigoHijo = $request->input('codigoHijo');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function ProductoEditar(Request $request)
    {
        $data = Producto::findOrfail($request->input('idProducto'));
        $data->idLinea = $request->input('idLinea');
        $data->sku = $request->input('sku');
        $data->nombre = $request->input('nombre');
        $data->marca = $request->input('marca');
        $data->formato = $request->input('formato');
        $data->sabor = $request->input('sabor');
        $data->unidadxCaja = $request->input('unidadxCaja');
        $data->unidadxPaquete = $request->input('unidadxPaquete');
        $data->cajaxpaquete = $request->input('cajaxpaquete');
        $data->codigoPadre = $request->input('codigoPadre');
        $data->codigoHijo = $request->input('codigoHijo');
        $data->save();
        return $data;
    }
    public static function ProductoBloquear(Request $request)
    {
        $data = Producto::findOrfail($request->input('idProducto'));
        $data->estado = 0;
        $data->save();
        return $data;
    }
    public static function ProductoRestablecer(Request $request)
    {
        $data = Producto::findOrfail($request->input('idProducto'));
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function ProductoListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
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
            p.estado,
            IF(p.estado = 1,'ACTIVO','INACTIVO') as estadoNombre
        FROM producto AS p
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE p.idCeo =  $idCeo"));
    }
    public static function ProductoListarActivos(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
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
            p.estado,
            IF(p.estado = 1,'ACTIVO','INACTIVO') as estadoNombre
        FROM producto AS p
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE p.idCeo =  $idCeo AND p.estado = 1"));
    }
    public static function ProductoImportarData(Request $request)
    {
        $archivoPlantilla = $request->file('productoExcel');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($archivoPlantilla);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            "A" => "sku",
            "B" => "nombre",
            "C" => "marca",
            "D" => "formato",
            "E" => "sabor",
            "F" => "unidadxCaja",
            "G" => "unidadxPaquete",
            "H" => "cajaxpaquete",
            "I" => "codigoPadre",
            "J" => "codigoHijo",
            "K" => "nombreLinea",
        ];
        $dataImportada = [];
        for ($i = $startRow; $i <= $max; $i++) {
            $data_row = [];
            foreach ($columns as $col => $field) {
                $val = $workSheet->getCell("$col$i")->getValue();
                $data_row[$field] = trim($val);
                $data_row['idCeo'] = $request->input('idCeo');
            }
            $dataImportada[] = $data_row;
        }
        $data = array_chunk($dataImportada, 1000);
        foreach ($data as $dt) {
            foreach ($dt as $dtx) {
                $idLinea = "";
                $obj = Producto::where('idCeo', $dtx['idCeo'])->where('sku', $dtx['sku'])->first();
                $objLinea = Linea::where('idCeo', $dtx['idCeo'])->where('nombre', $dtx['nombreLinea'])->first();
                if ($objLinea != null) {
                    $idLinea = $objLinea->idLinea;
                } else {
                    $res = new Request([
                        'idCeo' => $dtx['idCeo'],
                        'nombre' => $dtx['nombreLinea'],
                    ]);
                    $data = Linea::LineaRegistrar($res);
                    $idLinea = $data->idLinea;
                }
                if ($obj != null) {
                    $obj->nombre = trim($dtx['nombre']);
                    $obj->marca = trim($dtx['marca']);
                    $obj->formato = trim($dtx['formato']);
                    $obj->sabor = trim($dtx['sabor']);
                    $obj->unidadxCaja = trim($dtx['unidadxCaja']);
                    $obj->unidadxPaquete = trim($dtx['unidadxPaquete']);
                    $obj->cajaxpaquete = trim($dtx['cajaxpaquete']);
                    $obj->codigoPadre = trim($dtx['codigoPadre']);
                    $obj->codigoHijo = trim($dtx['codigoHijo']);
                    $obj->idLinea = $idLinea;
                    $obj->save();
                } else {
                    $res = new Request([
                        'idCeo' => $dtx['idCeo'],
                        'sku' => trim($dtx['sku']),
                        'nombre' => trim($dtx['nombre']),
                        'marca' => trim($dtx['marca']),
                        'formato' => trim($dtx['formato']),
                        'sabor' => trim($dtx['sabor']),
                        'unidadxCaja' => trim($dtx['unidadxCaja']),
                        'unidadxPaquete' => trim($dtx['unidadxPaquete']),
                        'cajaxpaquete' => trim($dtx['cajaxpaquete']),
                        'codigoPadre' => trim($dtx['codigoPadre']),
                        'codigoHijo' => trim($dtx['codigoHijo']),
                        'idLinea' => $idLinea,
                    ]);
                    Producto::ProductoRegistrar($res);
                }
            }
        }
    }
    public static function ProductoDetalle($sku, $idCeo)
    {
        return collect(DB::select(DB::raw("SELECT
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
            p.estado,
            IF(p.estado = 1,'ACTIVO','INACTIVO') as estadoNombre
        FROM producto AS p
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE p.sku = $sku AND p.idCeo = $idCeo")))->first();
    }
    public static function ProductoCodigoPadreDetalle($codigoPadre, $idCeo)
    {
        return collect(DB::select(DB::raw("SELECT
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
            p.estado,
            IF(p.estado = 1,'ACTIVO','INACTIVO') as estadoNombre
        FROM producto AS p
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE p.codigoPadre = $codigoPadre AND p.idCeo = $idCeo")))->first();
    }
}
