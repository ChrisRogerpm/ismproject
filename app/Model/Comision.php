<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    protected $table = "comision";
    protected $primaryKey = "idComision";
    protected $fillable = [
        'idCeo',
        'nombre',
        'fecha',
        'estado',
    ];
    public $timestamps = false;
    public static function ComisionRegistrar(Request $request)
    {
        $data = new Comision();
        $data->idCeo = $request->input('idCeo');
        $data->nombre = $request->input('nombre');
        $data->fecha = $request->input('fecha');
        $data->estado = 0;
        $data->save();
        return $data;
    }
    public static function ComisionEditar(Request $request)
    {
        $data = Comision::findOrfail($request->input('idComision'));
        $data->nombre = $request->input('nombre');
        $data->fecha = $request->input('fecha');
        $data->save();
        return $data;
    }
    public static function ComisionListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            c.idComision,
            c.nombre,
            c.fecha,
            IF(c.estado = 1,'ACTIVO','INACTIVO') AS estadoNombre,
            c.estado
        FROM comision AS c
        WHERE c.idCeo = $idCeo
        "));
    }
    public static function ComisionActivar(Request $request)
    {
        $data = Comision::findOrfail($request->input('idComision'));
        $data->estado = 1;
        $data->save();
        DB::table('Comision')
            ->where('idComision', '!=', $data->idComision)
            ->where('idCeo', $data->idCeo)
            ->update(['estado' => 0]);
    }
    public static function ComisionImportarData(Request $request)
    {
        $archivoComision = $request->file('archivoComision');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($archivoComision);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            "A" => "codigoPadre",
            "D" => "condicion",
            "E" => "cantidadValor",
            "F" => "comisionPtoVenta",
            "G" => "comisionDistribuidor"
        ];
        $dataImportada = [];
        for ($i = $startRow; $i <= $max; $i++) {
            $data_row = [];
            foreach ($columns as $col => $field) {
                $val = $workSheet->getCell("$col$i")->getValue();
                $data_row[$field] = trim($val);
            }
            $dataImportada[] = $data_row;
        }
        $dataImportada = collect($dataImportada);
        $ListaDetalle = [];
        foreach ($dataImportada as $data) {
            $objProducto = Producto::ProductoCodigoPadreDetalle($data['codigoPadre'], $request->input('idCeo'));
            if ($objProducto != null) {
                $ListaDetalle[] = [
                    'codigoPadre' => trim($data['codigoPadre']),
                    'idProducto' => $objProducto->idProducto,
                    'nombreProducto' => $objProducto->nombreProducto,
                    'condicion' => $data['condicion'],
                    'cantidadValor' => $data['cantidadValor'],
                    'comisionPtoVenta' => str_replace(',', '.', $data['comisionPtoVenta']),
                    'comisionDistribuidor' => str_replace(',', '.', $data['comisionDistribuidor'])
                ];
            }
        }
        return collect($ListaDetalle);
    }
}
