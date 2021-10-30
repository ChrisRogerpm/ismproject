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
    public static function ProductoCodigoPadreListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            p.codigoPadre,
            CONCAT(
            (SELECT px.marca FROM producto AS px WHERE px.codigoPadre = p.codigoPadre ORDER BY px.marca DESC LIMIT 1),
            ' ',
            (SELECT px.formato FROM producto AS px WHERE px.codigoPadre = p.codigoPadre ORDER BY px.formato DESC LIMIT 1),
            ' ',
            (SELECT px.sabor FROM producto AS px WHERE px.codigoPadre = p.codigoPadre ORDER BY px.sabor DESC LIMIT 1)
            ) AS nombreProducto
        FROM producto AS p
        WHERE p.idCeo = $idCeo
        GROUP BY p.codigoPadre"));
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
        $objRetorno = new \stdClass();
        $objRetorno->respuesta = true;
        $objRetorno->mensaje = "";
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
        $fila = 1;
        foreach ($columns as $col => $field) {
            $val = trim($workSheet->getCell("$col$fila")->getValue());
            switch ($col) {
                case "A":
                    if ($val != "CODALT") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna A";
                        break 2;
                    }
                    break;
                case "B":
                    if ($val != "PRODUCTO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna B";
                        break 2;
                    }
                    break;
                case "C":
                    if ($val != "MARCA") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna C";
                        break 2;
                    }
                    break;
                case "D":
                    if ($val != "FORMATO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna D";
                        break 2;
                    }
                    break;
                case "E":
                    if ($val != "SABOR") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna E";
                        break 2;
                    }
                    break;
                case "F":
                    if ($val != "CAJA") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna F";
                        break 2;
                    }
                    break;
                case "G":
                    if ($val != "PAQUETE") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna G";
                        break 2;
                    }
                    break;
                case "H":
                    if ($val != "CAJAXPAQUETE") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna H";
                        break 2;
                    }
                    break;
                case "I":
                    if ($val != "CODIGO PADRE") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna I";
                        break 2;
                    }
                    break;
                case "J":
                    if ($val != "CODIGO HIJO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna J";
                        break 2;
                    }
                    break;
                case "K":
                    if ($val != "LINEA") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna K";
                        break 2;
                    }
                    break;
            }
        }
        if ($objRetorno->respuesta) {
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
                    $obj = Producto::where('idCeo', $dtx['idCeo'])->where('sku', str_pad(trim($dtx['sku']), 3, '0', STR_PAD_LEFT))->first();
                    $objLinea = Linea::where('idCeo', $dtx['idCeo'])->where('nombre', trim($dtx['nombreLinea']))->first();
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
                            'sku' => str_pad(trim($dtx['sku']), 3, '0', STR_PAD_LEFT),
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
        return $objRetorno;
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
            p.codigoPadre,
            CONCAT(
            (SELECT px.marca FROM producto AS px WHERE px.codigoPadre = p.codigoPadre ORDER BY px.marca DESC LIMIT 1),
            ' ',
            (SELECT px.formato FROM producto AS px WHERE px.codigoPadre = p.codigoPadre ORDER BY px.formato DESC LIMIT 1),
            ' ',
            (SELECT px.sabor FROM producto AS px WHERE px.codigoPadre = p.codigoPadre ORDER BY px.sabor DESC LIMIT 1)
            ) AS nombreProducto
        FROM producto AS p
        WHERE p.idCeo = $idCeo AND p.codigoPadre = '$codigoPadre'
        GROUP BY p.codigoPadre")))->first();
    }
}
