<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "cliente";
    protected $primaryKey = "idCliente";
    protected $fillable = [
        'idCeo',
        'nroDocumento',
        'ruta',
        'modulo',
        'codigoCliente',
        'nombreRazonSocial',
        'direccion',
        'referencia',
        'negocio',
        'canal',
        'subCanal',
        'giroNegocio',
        'distrito',
        'diaVisita',
        'diaReparto',
        'telefono',
        'codigoAntiguo',
        'usuario',
        'contrasenia',
        'estado',
    ];
    public $timestamps = false;
    public static function ClienteListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        DB::statement(DB::raw('set @row:=0'));
        return DB::select(DB::raw("SELECT
            @row:=@row+1 as nroSecuencia,
            c.idCliente,
            c.idCeo,
            IFNULL(c.nombreRazonSocial,'--') AS nombreRazonSocial,
            IFNULL(c.direccion,'--') AS direccion,
            c.nroDocumento,
            c.ruta,
            c.modulo,
            IF(c.estado = 1,'ACTIVO','INACTIVO') as estado
        FROM cliente AS c
        WHERE c.idCeo = $idCeo
        ORDER BY c.idCliente DESC"));
    }
    public static function ClienteRegistrar(Request $request)
    {
        $data = new Cliente();
        $data->idCeo = $request->input('idCeo');
        $data->nroDocumento = $request->input('nroDocumento');
        $data->ruta = $request->input('ruta');
        $data->modulo = $request->input('modulo');
        $data->codigoCliente = $request->input('codigoCliente');
        $data->nombreRazonSocial = $request->input('nombreRazonSocial');
        $data->direccion = $request->input('direccion');
        $data->referencia = $request->input('referencia');
        $data->negocio = $request->input('negocio');
        $data->canal = $request->input('canal');
        $data->subCanal = $request->input('subCanal');
        $data->giroNegocio = $request->input('giroNegocio');
        $data->distrito = $request->input('distrito');
        $data->diaVisita = $request->input('diaVisita');
        $data->diaReparto = $request->input('diaReparto');
        $data->telefono = $request->input('telefono');
        $data->codigoAntiguo = $request->input('codigoAntiguo');
        $data->usuario = $request->input('usuario');
        $data->contrasenia = $request->input('contrasenia');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function ClienteEditar(Request $request)
    {
        $data = Cliente::findOrfail($request->input('idCliente'));
        $data->nroDocumento = $request->input('nroDocumento');
        $data->ruta = $request->input('ruta');
        $data->modulo = $request->input('modulo');
        $data->codigoCliente = $request->input('codigoCliente');
        $data->nombreRazonSocial = $request->input('nombreRazonSocial');
        $data->direccion = $request->input('direccion');
        $data->referencia = $request->input('referencia');
        $data->negocio = $request->input('negocio');
        $data->canal = $request->input('canal');
        $data->subCanal = $request->input('subCanal');
        $data->giroNegocio = $request->input('giroNegocio');
        $data->distrito = $request->input('distrito');
        $data->diaVisita = $request->input('diaVisita');
        $data->diaReparto = $request->input('diaReparto');
        $data->telefono = $request->input('telefono');
        $data->codigoAntiguo = $request->input('codigoAntiguo');
        $data->usuario = $request->input('usuario');
        $data->contrasenia = $request->input('contrasenia');
        $data->save();
        return $data;
    }
    public static function ClienteBloquear(Request $request)
    {
        $data = Cliente::findOrfail('idCliente');
        $data->estado = 0;
        $data->save();
        return $data;
    }
    public static function ClienteRestablecer(Request $request)
    {
        $data = Cliente::findOrfail('idCliente');
        $data->estado = 1;
        $data->save();
        return $data;
    }
    public static function ClienteImportarData(Request $request)
    {
        $archivoPlantilla = $request->file('clienteExcel');
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
            "A" => "nroDocumento",
            "B" => "ruta",
            "C" => "modulo",
            "D" => "codigoCliente",
            "F" => "nombreRazonSocial",
            "G" => "direccion",
            "H" => "referencia",
            "I" => "negocio",
            "J" => "canal",
            "K" => "subCanal",
            "L" => "giroNegocio",
            "M" => "distrito",
            "N" => "diaVisita",
            "O" => "diaReparto",
            "P" => "telefono",
            "Q" => "codigoAntiguo",
            "S" => "usuario",
            "T" => "contrasenia",
        ];
        $fila = 1;
        foreach ($columns as $col => $field) {
            $val = trim($workSheet->getCell("$col$fila")->getValue());
            switch ($col) {
                case "A":
                    if ($val != "DNI") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna A";
                        break 2;
                    }
                    break;
                case "B":
                    if ($val != "RUTA") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna B";
                        break 2;
                    }
                    break;
                case "C":
                    if ($val != "MODULO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna C";
                        break 2;
                    }
                    break;
                case "D":
                    if ($val != "CODIGO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna D";
                        break 2;
                    }
                    break;
                case "F":
                    if ($val != "NOMBRE/RAZON SOCIAL") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna F";
                        break 2;
                    }
                    break;
                case "G":
                    if ($val != "DIRECCION") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna G";
                        break 2;
                    }
                    break;
                case "H":
                    if ($val != "REFERENCIA") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna H";
                        break 2;
                    }
                    break;
                case "I":
                    if ($val != "NEGOCIO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna I";
                        break 2;
                    }
                    break;
                case "J":
                    if ($val != "CANAL") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna J";
                        break 2;
                    }
                    break;
                case "K":
                    if ($val != "SUB CANAL") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna K";
                        break 2;
                    }
                    break;
                case "L":
                    if ($val != "GIRO NEGOCIO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna L";
                        break 2;
                    }
                    break;
                case "M":
                    if ($val != "DISTRITO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna M";
                        break 2;
                    }
                    break;
                case "N":
                    if ($val != "DIA VISITA PREVENTA") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna N";
                        break 2;
                    }
                    break;
                case "O":
                    if ($val != "DIA REPARTO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna O";
                        break 2;
                    }
                    break;
                case "P":
                    if ($val != "TELEFONO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna P";
                        break 2;
                    }
                    break;
                case "Q":
                    if ($val != "CODIGO ANTIGUO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna Q";
                        break 2;
                    }
                    break;
                case "S":
                    if ($val != "USUARIO") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna S";
                        break 2;
                    }
                    break;
                case "T":
                    if ($val != "CONTRASEÑA") {
                        $objRetorno->respuesta = false;
                        $objRetorno->mensaje = "El documento no cumple con el formato indicado: revisar la columna T";
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
                    $data_row['estado'] = 1;
                }
                $dataImportada[] = $data_row;
            }
            $clientes = array_chunk($dataImportada, 1000);
            Cliente::where('idCeo', $request->input('idCeo'))->delete();
            foreach ($clientes as $cliente) {
                Cliente::insert($cliente);
            }
            // Agrega rutas nuevas
            Ruta::RutaActualizar($request);
        }
        return $objRetorno;
    }
}
