<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Bonificacion extends Model
{
    protected $table = "bonificacion";
    protected $primaryKey = "idBonificacion";
    protected $fillable = [
        'idCeo',
        'nombreBonificacion',
        'fechaInicio',
        'fechaFin',
        'diasBonificar',
        'estado',
    ];
    public $timestamps = false;
    public static function BonificacionListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
        b.idBonificacion,
        b.idCeo,
        b.nombreBonificacion,
        b.fechaInicio,
        b.fechaFin,
        b.diasBonificar,
        b.estado,
        IF(b.estado = 1,'ACTIVO','INACTIVO') as estadoNombre
        FROM bonificacion as b
        WHERE b.idCeo = $idCeo"));
    }
    public static function BonificacionRegistrar(Request $request)
    {
        $data = new Bonificacion();
        $data->idCeo = $request->input('idCeo');
        $data->fechaInicio = $request->input('fechaInicio');
        $data->fechaFin = $request->input('fechaFin');
        $data->diasBonificar = $request->input('diasBonificar');
        $data->estado = 0;
        $data->save();
        return $data;
    }
    public static function BonificacionEditar(Request $request)
    {
        $data = Bonificacion::findOrfail($request->input('idBonificacion'));
        $data->fechaInicio = $request->input('fechaInicio');
        $data->fechaFin = $request->input('fechaFin');
        $data->diasBonificar = $request->input('diasBonificar');
        $data->save();
        return $data;
    }
    public static function BonificacionActivar(Request $request)
    {
        $data = Bonificacion::findOrfail($request->input('idBonificacion'));
        $data->estado = 1;
        $data->save();

        DB::table('bonificacion')
            ->where('idBonificacion', '!=', $data->idBonificacion)
            ->where('idCeo', $data->idCeo)
            ->update(['estado' => 0]);
    }
    public static function BonificacionListarProcesado(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
        co.nombreCeo,
        l.nombre AS nombreLinea,
        p.marca,
        p.formato,
        p.codigoPadre,
        IF(bd.condicionAt = 1,p.unidadxCaja,p.unidadxPaquete) AS cajaX,
        IF(bd.condicionAt = 1,'CAJA','PAQUETE') AS condicionAt,
        p.sku,
        bd.nroBotellasBonificar,
        CONCAT(
        (SELECT px.marca FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar),
        ' / ',
        (SELECT px.formato FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar)
        ) AS marcaFormatoBonificar,
        (SELECT px.sku FROM producto AS px WHERE px.idProducto = bd.idProductoBonificar) AS saborBonificar,
        CONCAT(DATEDIFF(b.fechaFin, b.fechaInicio),' Días') AS diasBonificar,
        b.fechaInicio,
        b.fechaFin
        FROM bonificaciondetalle AS bd
        INNER JOIN bonificacion AS b ON b.idBonificacion = bd.idBonificacion
        INNER JOIN centrooperativo AS co ON co.idCeo = b.idCeo
        INNER JOIN producto AS p ON p.idProducto = bd.idProducto
        INNER JOIN linea AS l ON l.idLinea = p.idLinea
        WHERE bd.idBonificacion = (SELECT bf.idBonificacion FROM bonificacion AS bf WHERE bf.estado = 1 ORDER BY bf.idBonificacion LIMIT 1) AND b.idCeo = $idCeo"));
    }
    public static function BonificacionImportarData(Request $request)
    {
        $archivoBonificacion = $request->file('archivoBonificacion');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($archivoBonificacion);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            "G" => "condicionAt",
            "H" => "sku",
            "I" => "nroBotellasBonificar",
            "K" => "idProductoBonificar"
        ];
        $dataImportada = [];
        for ($i = $startRow; $i <= $max; $i++) {
            $data_row = [];
            foreach ($columns as $col => $field) {
                $val = $workSheet->getCell("$col$i")->getValue();
                $data_row[$field] = $val;
            }
            $dataImportada[] = $data_row;
        }
        $dataImportada = collect($dataImportada);
        $ListaDetalle = [];
        foreach ($dataImportada as $data) {
            $objProducto = Producto::ProductoDetalle($data['sku'], $request->input('idCeo'));
            if ($objProducto != null) {
                $objProductoBonificar = Producto::where('sku', $data['idProductoBonificar'])->where('idCeo', $request->input('idCeo'))->first();
                $ListaDetalle[] = [
                    'idProducto' => $objProducto->idProducto,
                    'nombreLinea' => $objProducto->nombreLinea,
                    'sku' => $objProducto->sku,
                    'nombreProducto' => $objProducto->nombreProducto,
                    'marca' => $objProducto->marca,
                    'formato' => $objProducto->formato,
                    'sabor' => $objProducto->sabor,
                    'caja' => $objProducto->caja,
                    'paquete' => $objProducto->paquete,
                    'cajaxpaquete' => $objProducto->cajaxpaquete,
                    'codigoPadre' => $objProducto->codigoPadre,
                    'codigoHijo' => $objProducto->codigoHijo,
                    'estado' => $objProducto->estado,
                    'estadoNombre' => $objProducto->estadoNombre,
                    'cajaX' => $data['condicionAt'] == "CAJA" ? $objProducto->caja : $objProducto->paquete,
                    'condicionAt' => $data['condicionAt'] == "CAJA" ? 1 : 0,
                    'nroBotellasBonificar' => $data['nroBotellasBonificar'],
                    'marcaFormatoBonificar' => $objProductoBonificar == null ? '' : $objProductoBonificar->marca . '/' . $objProductoBonificar->formato,
                    'idProductoBonificar' => $objProductoBonificar == null ? '' : $objProductoBonificar->idProducto,
                ];
            }
        }
        return collect($ListaDetalle);
    }
}
