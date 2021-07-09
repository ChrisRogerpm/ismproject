<?php

namespace App\Utilitarios;

use Carbon\Carbon;
use App\Model\Reporte;
use App\Model\Solicitud;
use App\Model\Liquidacion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Excel
{
    public static function CopiarArchivosTmp($archivo)
    {
        $rutaDirectorio = public_path('Excels');
        $extension = $archivo->getClientOriginalExtension();
        $ArchivoNombre = Str::random(8) . "." . $extension;
        $archivo->move($rutaDirectorio, $ArchivoNombre);
        return $ArchivoNombre;
    }
    public static function ImportarDataTB_UNI(Request $request)
    {
        $path = public_path('Excels' . DIRECTORY_SEPARATOR);
        $archivoPlantilla = $path . $request->input('archivoPlantilla');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly(["TB_UNI"]);
        $spreadSheet = $reader->load($archivoPlantilla);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            "A" => "CODALT",
            "B" => "SABOR",
            "C" => "CAJA",
            "D" => "PAQUETE",
            "E" => "CAJAXPAQUETE",
            "F" => "CODIGO PADRE",
            "G" => "CODIGO HIJO",
            "H" => "LINEA",
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
        return collect($dataImportada);
    }
    public static function ImportarDataGGVVRUTA(Request $request)
    {
        $path = public_path('Excels' . DIRECTORY_SEPARATOR);
        $archivoPlantilla = $path . $request->input('archivoPlantilla');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly(["GGVVRUTA"]);
        $spreadSheet = $reader->load($archivoPlantilla);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            "A" => "RUTA",
            "B" => "LINEA",
            "C" => "MESA",
            "D" => "GESTORES",
            "E" => "SUPERVISOR",
            "F" => "TELEFONO",
            "G" => "DNI",
            "H" => "CODIGO",
            "I" => "MARCAS",
            "J" => "SUPERVISOR",
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
        return collect($dataImportada);
    }
    public static function ImportarDataCLIENTEPEDIDO(Request $request)
    {
        $path = public_path('Excels' . DIRECTORY_SEPARATOR);
        $archivoPedido = $path . $request->input('archivoPedido');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly(["CLIENTE"]);
        $spreadSheet = $reader->load($archivoPedido);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            'A' => 'NROPEDIDO',
            'B' => 'COD_CLIE',
            'C' => 'CLIENTE',
            'D' => 'DIRECCION',
            'E' => 'REFERENCIA',
            'F' => 'TIPO_DOC',
            'G' => 'NRO_DOC',
            'H' => 'RUTA',
            'I' => 'MODULO',
            'J' => 'VISITA',
            'K' => 'LATITUD',
            'L' => 'LONGITUD',
            'M' => 'GIRO',
            'N' => 'EMAIL',
            'O' => 'TELEFONO',
            'P' => 'LUGAR',
            'Q' => 'SUCURSAL',
            'R' => 'CANAL',
            'S' => 'LPRECIO',
        ];
        $dataImportada = [];
        for ($i = $startRow; $i <= $max; $i++) {
            $data_row = [];
            foreach ($columns as $col => $field) {
                $val = $workSheet->getCell("$col$i")->getValue();
                $data_row[$field] = $val;
                if ($field == "CANAL") {
                    $data_row[$field] = "B2B";
                }
            }
            $dataImportada[] = $data_row;
        }
        return collect($dataImportada);
    }
    public static function ImportarDataBONIFICACIONES(Request $request)
    {
        $path = public_path('Excels' . DIRECTORY_SEPARATOR);
        $archivoBonificaciones = $path . $request->input('archivoBonificaciones');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly(["CUADRO DE BONIFICACIONES"]);
        $spreadSheet = $reader->load($archivoBonificaciones);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            'E' => 'COD',
            'F' => 'Caja X',
            'H' => 'SKU',
            'I' => 'Bonif (Botellas)',
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
        return collect($dataImportada);
    }
    public static function ImportarDataPEDIDO(Request $request)
    {
        $path = public_path('Excels' . DIRECTORY_SEPARATOR);
        $archivoPedido = $path . $request->input('archivoPedido');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly(["PEDIDO"]);
        $spreadSheet = $reader->load($archivoPedido);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            "A" => "NROPEDIDO",
            "B" => "FEPVTA",
            "C" => "FEMOVI",
            "D" => "CODALT",
            "E" => "CANTIDAD",
            "F" => "PRECIO",
            "G" => "PDSCTO",
            "H" => "DESCTO",
            "I" => "TDOCTO",
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
        return collect($dataImportada);
    }
    public static function ProcesarDataImportadaPedidoExcel(Request $request)
    {
        $DataTB_UNI = Excel::ImportarDataTB_UNI($request);
        $DataPEDIDO = Excel::ImportarDataPEDIDO($request);
        $DataBONIFICACIONES = Excel::ImportarDataBONIFICACIONES($request);
        $DataPedidogroupBy = $DataPEDIDO->groupBy('NROPEDIDO');
        $nuevaData = [];

        foreach ($DataPedidogroupBy as $dpg) {
            $CodigosCODALT = [];
            $CodigosCOD = [];
            foreach ($dpg as $dp) {
                $CodigosCODALT[] = $dp['CODALT'];
                $objPlantilla = $DataTB_UNI->where('CODALT', $dp['CODALT'])->first();
                $obBonificaciones = $DataBONIFICACIONES->where('SKU', $dp['CODALT'])->first();
                $CodigosCOD[] = [
                    'COD' => $obBonificaciones != null ? $obBonificaciones['COD'] : '',
                    'Caja X' => $obBonificaciones != null ? $obBonificaciones['Caja X'] : '',
                    'Boni' => $obBonificaciones != null ? $obBonificaciones['Bonif (Botellas)'] : '',
                    'CantidadPedido' => $dp['CANTIDAD'],
                    'CODALT' => $dp['CODALT'],
                    'NROPEDIDO' => $dp['NROPEDIDO'],
                    'FEPVTA' => $dp['FEPVTA'],
                    'FEMOVI' => $dp['FEMOVI'],
                ];
                $nuevaData[] = [
                    'NROPEDIDO' => $dp['NROPEDIDO'],
                    'FEPVTA' => $dp['FEPVTA'],
                    'FEMOVI' => $dp['FEMOVI'],
                    'CODALT' => $dp['CODALT'],
                    'CANTIDAD' => Excel::CalcularCantidadPaqueteProducto($objPlantilla['PAQUETE'], $dp['CANTIDAD']),
                    'PRECIO' => $dp['PRECIO'],
                    'PDSCTO' => $dp['PDSCTO'],
                    'DESCTO' => $dp['DESCTO'],
                    'TDOCTO' => $dp['TDOCTO'],
                ];
            }
            $ProductosBonificaciones = $DataBONIFICACIONES->whereIn('SKU', $CodigosCODALT)->groupBy('COD');
            $CodigosCOD = collect($CodigosCOD);
            foreach ($ProductosBonificaciones as $boni) {
                $cantidadProductosHijos = $CodigosCOD->where('COD', $boni[0]['COD']);
                $sumaCantidades = 0;
                $cantidadCajaX = 0;
                $boniCaja = 0;
                $NROPEDIDO = 0;
                $FEPVTA = 0;
                $FEMOVI = 0;
                $CODALT = 0;
                foreach ($cantidadProductosHijos as $cph) {
                    $sumaCantidades += $cph['CantidadPedido'];
                    $cantidadCajaX = $cph['Caja X'];
                    $boniCaja =  $cph['Boni'];
                    $NROPEDIDO = $cph['NROPEDIDO'];
                    $FEPVTA = $cph['FEPVTA'];
                    $FEMOVI = $cph['FEMOVI'];
                    $CODALT = $cph['CODALT'];
                }
                $numeroBonificaciones = Excel::CalcularBonificacionProducto($cantidadCajaX, $sumaCantidades);
                if ($numeroBonificaciones > 0) {
                    $cantidad = ($numeroBonificaciones * $boniCaja) / 100;
                    $nuevaData[] = [
                        'NROPEDIDO' => $NROPEDIDO,
                        'FEPVTA' => $FEPVTA,
                        'FEMOVI' => $FEMOVI,
                        'CODALT' => $CODALT,
                        'CANTIDAD' => $cantidad,
                        'PRECIO' => 0,
                        'PDSCTO' => 0,
                        'DESCTO' => 0,
                        'TDOCTO' => 207,
                    ];
                }
            }
        }
        return collect($nuevaData);
    }
    public static function ImportarDataDATA_CLI(Request $request)
    {
        $path = public_path('Excels' . DIRECTORY_SEPARATOR);
        $archivoPlantilla = $path . $request->input('archivoPlantilla');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly(["DATA_CLI"]);
        $spreadSheet = $reader->load($archivoPlantilla);
        $workSheet = $spreadSheet->getActiveSheet();
        $startRow = 2;
        $max = $spreadSheet->getActiveSheet()->getHighestRow();
        $columns = [
            "A" => "DNI",
            "B" => "RUTA",
            "C" => "MODULO",
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
        return collect($dataImportada);
    }
    public static function ProcesarDataImportadaClientePedidoExcel(Request $request)
    {
        $DataGGVVRUTA = Excel::ImportarDataGGVVRUTA($request);
        $DataCLIENTEPEDIDO = Excel::ImportarDataCLIENTEPEDIDO($request);
        $DataDATA_CLI = Excel::ImportarDataDATA_CLI($request);
        $nuevaData = [];
        foreach ($DataCLIENTEPEDIDO as $cp) {
            //Ruta y Modulo están
            $Cli = $DataDATA_CLI->where('DNI', $cp['NRO_DOC'])->first();
            $pdv = $Cli != null ? $DataGGVVRUTA->where('RUTA', $Cli['RUTA'])->first() : null;
            $tipoDocumento = strlen($cp['NRO_DOC']) <= 8 ? 0 : 1;
            $nroDocumento = $tipoDocumento == 1 ? Excel::CompletadorCerosDNI($cp['NRO_DOC']) : $cp['NRO_DOC'];
            $nuevaData[] = [
                'NROPEDIDO' => $cp['NROPEDIDO'],
                'COD_CLIE' => $cp['COD_CLIE'],
                'CLIENTE' => $cp['CLIENTE'],
                'DIRECCION' => $cp['DIRECCION'],
                'TIPO_DOC' => $tipoDocumento,
                'NRO_DOC' => $nroDocumento,
                'RUTA' => $Cli['RUTA'], //$cp['RUTA'],
                'MODULO' => $Cli['MODULO'],
                'VISITA' => $cp['VISITA'],
                'LATITUD' => $cp['LATITUD'],
                'LONGITUD' => $cp['LONGITUD'],
                'GIRO' => $cp['GIRO'],
                'EMAIL' => $cp['EMAIL'],
                'TELEFONO' => $cp['TELEFONO'],
                'LUGAR' => $cp['LUGAR'],
                'SUCURSAL' => $cp['SUCURSAL'],
                'CANAL' => $cp['CANAL'],
                'REFERENCIA' => $cp['REFERENCIA'],
                'LPRECIO' => $cp['LPRECIO'],
                'PDV' => $pdv != null ? $pdv['CODIGO'] : '',
            ];
        }
        return collect($nuevaData);
    }
    public static function CalcularCantidadPaqueteProducto($CantidadPaquete, $CantidadUnidad)
    {
        $calculado = $CantidadUnidad / $CantidadPaquete;
        return $calculado;
    }
    public static function GenerarExcelPedidoCorregido(Request $request)
    {
        $DataPEDIDOProcesada = Excel::ProcesarDataImportadaPedidoExcel($request);
        $DataCLIENTEPEDIDOProcesada = Excel::ProcesarDataImportadaClientePedidoExcel($request);

        $nombreArchivo = 'Pedidos' . '_' . time() . '.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headerCliente = ['NROPEDIDO', 'COD_CLIE', 'CLIENTE', 'DIRECCION', 'TIPO_DOC', 'NRO_DOC', 'RUTA', 'MODULO', 'VISITA', 'LATITUD', 'LONGITUD', 'GIRO', 'EMAIL', 'TELEFONO', 'LUGAR', 'SUCURSAL', 'CANAL', 'REFERENCIA', 'LPRECIO', 'PDV'];
        $arrayKeys = array_keys($DataCLIENTEPEDIDOProcesada[0]);
        $columnsCli = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
        $fila = "";
        $sheet->setTitle('CLIENTE');
        //Cabeceras
        for ($i = 0; $i < count($headerCliente); $i++) {
            $celda = $columnsCli[$i] . '1';
            $sheet->setCellValue($celda, $headerCliente[$i]);
            $sheet->getStyle($celda)->getFont()->setBold(true);
        }
        $fila = 2;
        //Cuerpo Columnas Data
        for ($x = 0; $x < count($DataCLIENTEPEDIDOProcesada); $x++) {
            for ($i = 0; $i < count($arrayKeys); $i++) {
                $sheet->setCellValue($columnsCli[$i] . $fila, $DataCLIENTEPEDIDOProcesada[$x][$arrayKeys[$i]]);
            }
            $fila++;
        }

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $spreadsheet->getActiveSheet()->setTitle('CLIENTE');

        $sheet = $spreadsheet->getActiveSheet();

        $header = ['NROPEDIDO', 'FEPVTA', 'FEMOVI', 'CODALT', 'CANTIDAD', 'PRECIO', 'PDSCTO', 'DESCTO', 'TDOCTO'];
        $arrayKeys = array_keys($DataPEDIDOProcesada[0]);
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        $fila = "";
        $sheet->setTitle('PEDIDO');
        //Cabeceras
        for ($i = 0; $i < count($header); $i++) {
            $celda = $columns[$i] . '1';
            $sheet->setCellValue($celda, $header[$i]);
            $sheet->getStyle($celda)->getFont()->setBold(true);
        }
        $fila = 2;
        //Cuerpo Columnas Data
        for ($x = 0; $x < count($DataPEDIDOProcesada); $x++) {
            for ($i = 0; $i < count($arrayKeys); $i++) {
                $sheet->setCellValue($columns[$i] . $fila, $DataPEDIDOProcesada[$x][$arrayKeys[$i]]);
            }
            $fila++;
        }

        $archivo = "Excels/" . $nombreArchivo;
        $writer = new Xlsx($spreadsheet);
        $writer->save($archivo);
        return $nombreArchivo;
    }
    public static function CompletadorCerosDNI($valor)
    {
        return str_pad($valor, 8, '0', STR_PAD_LEFT);
    }
    public static function CalcularBonificacionProducto($cantidadUnidadesCaja, $cantidadUnidades)
    {
        return floor($cantidadUnidades / $cantidadUnidadesCaja);
    }
}
