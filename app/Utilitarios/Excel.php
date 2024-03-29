<?php

namespace App\Utilitarios;

use Carbon\Carbon;
use App\Model\Gestor;
use App\Model\Reporte;
use App\Model\Producto;
use App\Model\Solicitud;
use App\Model\Liquidacion;
use App\Model\Bonificacion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\ComisionDetalle;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Excel
{
    public const Columnas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    #region Generar Excel
    public static function GenerarExcelGestor(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $styleArrayTitulo = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => array(
                'name'      =>  'Calibri',
                'color' => ['argb' => '000000'],
                'bold' => true
            )
        ];
        $colorFondoCabecera = "F7CAAC";
        $Titulo1 = "LISTA DE GESTORES";
        $sheet = $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $sheet = $spreadsheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:I1')->setCellValue('A1', $Titulo1);
        $sheet->getStyle('A1:I1')->applyFromArray($styleArray);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
        $sheet->getStyle('A1')->getFont()->setBold(true);

        // $TituloCabecera = ['C.O', 'NRO DE LINEA', 'MARCA', 'FORMATO', 'COD', 'CAJA X', 'CONDICIÓN AT', 'SKU', 'BONIF(BOTELLAS)', 'MARCA/FORMATO BONIFICAR', '#DÍAS A BONIFICAR', 'DEL', 'AL'];
        $TituloCabecera = ['RUTA', 'LINEA', 'MESA', 'GESTOR', 'TELEFONO', 'DNI', 'CODIGO', 'MARCAS', 'SUPERVISOR'];
        $data1 = Gestor::GestorListarProcesado($request);
        $fila = 2;
        foreach ($TituloCabecera as $i => $d) {
            $sheet->setCellValue(Excel::Columnas[$i] . $fila, ucwords($TituloCabecera[$i]))->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
            $valor = Excel::Columnas[$i] . $fila;
            $sheet->getStyle($valor)->applyFromArray($styleArrayTitulo);
            $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($valor)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
            $sheet->getStyle($valor)->getFont()->setBold(true);
        }
        $fila++;
        $arrayData1 = [
            'ruta',
            'linea',
            'mesa',
            'gestor',
            'telefono',
            'dni',
            'codigo',
            'marcas',
            'supervisor',
        ];
        if (count($data1) > 0) {
            for ($x = 0; $x < count($data1); $x++) {
                for ($i = 0; $i < count($arrayData1); $i++) {
                    // $nombreKey = $arrayData1[$i];
                    $sheet->setCellValue(Excel::Columnas[$i] . $fila, $data1[$x][$arrayData1[$i]])->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
                    $valor = Excel::Columnas[$i] . $fila;
                    $sheet->getStyle($valor)->applyFromArray($styleArray);
                    $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
                }
                $fila++;
            }
        } else {
            $sheet->mergeCells('A' . $fila . ':I' . $fila)->setCellValue('A' . $fila, "NO HAY DATA QUE MOSTRAR");
            $sheet->getStyle('A' . $fila . ':I' . $fila . '')->applyFromArray($styleArray);
            $sheet->getStyle('A' . $fila)->getAlignment()->setHorizontal('center');
        }
        return $spreadsheet;
    }
    public static function GenerarExcelGestorExcel(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $styleArrayTitulo = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => array(
                'name'      =>  'Calibri',
                'color' => ['argb' => '000000'],
                'bold' => true
            )
        ];
        $colorFondoCabecera = "F7CAAC";

        $sheet = $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $sheet = $spreadsheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
        $sheet = $spreadsheet->getActiveSheet();

        $TituloCabecera = ['RUTA', 'LINEA', 'MESA', 'GESTOR', 'TELEFONO', 'DNI', 'CODIGO', 'SKU', 'MARCAS', 'SUPERVISOR'];
        $data1 = Gestor::GestorListarData($request);
        $fila = 1;
        foreach ($TituloCabecera as $i => $d) {
            $sheet->setCellValue(Excel::Columnas[$i] . $fila, ucwords($TituloCabecera[$i]))->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
            $valor = Excel::Columnas[$i] . $fila;
            $sheet->getStyle($valor)->applyFromArray($styleArrayTitulo);
            $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($valor)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
            $sheet->getStyle($valor)->getFont()->setBold(true);
        }
        $fila++;
        $arrayData1 = [
            'ruta',
            'linea',
            'mesa',
            'gestor',
            'telefono',
            'dni',
            'codigo',
            'sku',
            'marcas',
            'supervisor',
        ];
        if (count($data1) > 0) {
            for ($x = 0; $x < count($data1); $x++) {
                for ($i = 0; $i < count($arrayData1); $i++) {
                    // $nombreKey = $arrayData1[$i];
                    $sheet->setCellValue(Excel::Columnas[$i] . $fila, $data1[$x][$arrayData1[$i]])->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
                    $valor = Excel::Columnas[$i] . $fila;
                    $sheet->getStyle($valor)->applyFromArray($styleArray);
                    $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
                }
                $fila++;
            }
        } else {
            $sheet->mergeCells('A' . $fila . ':J' . $fila)->setCellValue('A' . $fila, "NO HAY DATA QUE MOSTRAR");
            $sheet->getStyle('A' . $fila . ':J' . $fila . '')->applyFromArray($styleArray);
            $sheet->getStyle('A' . $fila)->getAlignment()->setHorizontal('center');
        }
        return $spreadsheet;
    }
    public static function GenerarExcelBonificacion(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $styleArrayTitulo = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => array(
                'name'      =>  'Calibri',
                'color' => ['argb' => '000000'],
                'bold' => true
            )
        ];
        $colorFondoCabecera = "F7CAAC";
        $sheet = $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $sheet = $spreadsheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
        $sheet = $spreadsheet->getActiveSheet();

        $TituloCabecera = ['C.O', 'NRO DE LINEA', 'MARCA', 'FORMATO', 'COD', 'CAJA X', 'CONDICIÓN AT', 'SKU', 'BONIF(BOTELLAS)', 'MARCA/FORMATO BONIFICAR', 'SABOR A BONIFICAR', '#DÍAS A BONIFICAR', 'DEL', 'AL'];
        $data1 = Bonificacion::BonificacionListarProcesado($request);
        $fila = 1;
        foreach ($TituloCabecera as $i => $d) {
            $sheet->setCellValue(Excel::Columnas[$i] . $fila, ucwords($TituloCabecera[$i]))->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
            $valor = Excel::Columnas[$i] . $fila;
            $sheet->getStyle($valor)->applyFromArray($styleArrayTitulo);
            $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($valor)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
            $sheet->getStyle($valor)->getFont()->setBold(true);
        }
        $fila++;
        $arrayData1 = [
            'nombreCeo',
            'nombreLinea',
            'marca',
            'formato',
            'codigoPadre',
            'cajaX',
            'condicionAt',
            'sku',
            'nroBotellasBonificar',
            'marcaFormatoBonificar',
            'saborBonificar',
            'diasBonificar',
            'fechaInicio',
            'fechaFin',
        ];
        if (count($data1) > 0) {
            for ($x = 0; $x < count($data1); $x++) {
                for ($i = 0; $i < count($arrayData1); $i++) {
                    $nombreKey = $arrayData1[$i];
                    $sheet->setCellValue(Excel::Columnas[$i] . $fila, $data1[$x]->$nombreKey)->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
                    $valor = Excel::Columnas[$i] . $fila;
                    $sheet->getStyle($valor)->applyFromArray($styleArray);
                    $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
                }
                $fila++;
            }
        } else {
            $sheet->mergeCells('A' . $fila . ':N' . $fila)->setCellValue('A' . $fila, "NO HAY DATA QUE MOSTRAR");
            $sheet->getStyle('A' . $fila . ':N' . $fila . '')->applyFromArray($styleArray);
            $sheet->getStyle('A' . $fila)->getAlignment()->setHorizontal('center');
        }
        return $spreadsheet;
    }
    public static function GenerarExcelPedidoProcesado(Collection $Cliente, Collection $Pedido)
    {
        $spreadsheet = new Spreadsheet();
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $styleArrayTitulo = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => array(
                'name'      =>  'Calibri',
                'color' => ['argb' => '000000'],
                'bold' => true
            )
        ];
        $colorFondoCabecera = "F7CAAC";
        // $Titulo1 = "LISTA DE GESTORES";
        $sheet = $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $sheet = $spreadsheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('CLIENTE');
        $TituloCabecera = ['NROPEDIDO', 'COD_CLIE', 'CLIENTE', 'DIRECCION', 'TIPO_DOC', 'NRO_DOC', 'RUTA', 'MODULO', 'VISITA', 'LATITUD', 'LONGITUD', 'GIRO', 'EMAIL', 'TELEFONO', 'LUGAR', 'SUCURSAL', 'CANAL', 'REFERENCIA', 'LPRECIO', 'PDV'];
        $fila = 1;
        foreach ($TituloCabecera as $i => $d) {
            $sheet->setCellValue(Excel::Columnas[$i] . $fila, ucwords($TituloCabecera[$i]))->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
            $valor = Excel::Columnas[$i] . $fila;
            $sheet->getStyle($valor)->applyFromArray($styleArrayTitulo);
            $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($valor)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
            $sheet->getStyle($valor)->getFont()->setBold(true);
        }
        $fila++;
        $arrayData1 = [
            'NROPEDIDO',
            'COD_CLIE',
            'CLIENTE',
            'DIRECCION',
            'TIPO_DOC',
            'NRO_DOC',
            'RUTA',
            'MODULO',
            'VISITA',
            'LATITUD',
            'LONGITUD',
            'GIRO',
            'EMAIL',
            'TELEFONO',
            'LUGAR',
            'SUCURSAL',
            'CANAL',
            'REFERENCIA',
            'LPRECIO',
            'PDV',
        ];
        if (count($Cliente) > 0) {
            for ($x = 0; $x < count($Cliente); $x++) {
                for ($i = 0; $i < count($arrayData1); $i++) {
                    // $nombreKey = $arrayData1[$i];
                    $sheet->setCellValue(Excel::Columnas[$i] . $fila, $Cliente[$x][$arrayData1[$i]])->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
                    $valor = Excel::Columnas[$i] . $fila;
                    $sheet->getStyle($valor)->applyFromArray($styleArray);
                    $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
                }
                $fila++;
            }
        } else {
            $sheet->mergeCells('A' . $fila . ':T' . $fila)->setCellValue('A' . $fila, "NO HAY DATA QUE MOSTRAR");
            $sheet->getStyle('A' . $fila . ':T' . $fila . '')->applyFromArray($styleArray);
            $sheet->getStyle('A' . $fila)->getAlignment()->setHorizontal('center');
        }

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $spreadsheet->getActiveSheet()->setTitle('CLIENTE');

        $sheet = $spreadsheet->getActiveSheet();

        $header = ['NROPEDIDO', 'FEPVTA', 'FEMOVI', 'CODALT', 'CANTIDAD', 'PRECIO', 'PDSCTO', 'DESCTO', 'TDOCTO'];
        $arrayKeys = $header;
        $fila = "";
        $sheet->setTitle('PEDIDO');
        //Cabeceras
        for ($i = 0; $i < count($header); $i++) {
            $celda = Excel::Columnas[$i] . '1';
            $sheet->setCellValue($celda, $header[$i]);
            $sheet->getStyle($celda)->getFont()->setBold(true);
            $sheet->getStyle($celda)->applyFromArray($styleArrayTitulo);
            $sheet->getStyle($celda)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($celda)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
        }
        $fila = 2;
        //Cuerpo Columnas Data
        for ($x = 0; $x < count($Pedido); $x++) {
            for ($i = 0; $i < count($arrayKeys); $i++) {
                $sheet->setCellValue(Excel::Columnas[$i] . $fila, $Pedido[$x][$arrayKeys[$i]])->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
                $valor = Excel::Columnas[$i] . $fila;
                $sheet->getStyle($valor)->applyFromArray($styleArray);
                $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
            }
            $fila++;
        }
        $nombreArchivo = 'Pedidos' . '_' . time() . '.xlsx';
        $archivo = "Excels/" . $nombreArchivo;
        $writer = new Xlsx($spreadsheet);
        $writer->save($archivo);
        return $nombreArchivo;
    }
    public static function GenerarExcelReportePedido(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $styleArrayTitulo = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => array(
                'name'      =>  'Calibri',
                'color' => ['argb' => '000000'],
                'bold' => true
            )
        ];
        $colorFondoCabecera = "F7CAAC";
        $Titulo1 = "PEDIDOS MÁS VENDIDOS";
        $sheet = $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $sheet = $spreadsheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:C1')->setCellValue('A1', $Titulo1);
        $sheet->getStyle('A1:C1')->applyFromArray($styleArray);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $TituloCabecera = ['NRO DE PEDIDO', 'PRODUCTOS', 'TOTAL DE PEDIDO'];
        $data1 = Reporte::ReporteNroPedidoListar($request);
        $fila = 2;
        foreach ($TituloCabecera as $i => $d) {
            $sheet->setCellValue(Excel::Columnas[$i] . $fila, ucwords($TituloCabecera[$i]))->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
            $valor = Excel::Columnas[$i] . $fila;
            $sheet->getStyle($valor)->applyFromArray($styleArrayTitulo);
            $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($valor)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
            $sheet->getStyle($valor)->getFont()->setBold(true);
        }
        $fila++;
        $arrayData1 = [
            'nroPedido',
            'productosInvolucrados',
            'TotalPedido',
        ];
        if (count($data1) > 0) {
            for ($x = 0; $x < count($data1); $x++) {
                for ($i = 0; $i < count($arrayData1); $i++) {
                    $nombreKey = $arrayData1[$i];
                    $sheet->setCellValue(Excel::Columnas[$i] . $fila, $data1[$x]->$nombreKey)->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
                    $valor = Excel::Columnas[$i] . $fila;
                    $sheet->getStyle($valor)->applyFromArray($styleArray);
                    $sheet->getStyle($valor)->getAlignment()->setHorizontal('left');
                }
                $fila++;
            }
        } else {
            $sheet->mergeCells('A' . $fila . ':C' . $fila)->setCellValue('A' . $fila, "NO HAY DATA QUE MOSTRAR");
            $sheet->getStyle('A' . $fila . ':C' . $fila . '')->applyFromArray($styleArray);
            $sheet->getStyle('A' . $fila)->getAlignment()->setHorizontal('center');
        }
        return $spreadsheet;
    }
    public static function GenerarExcelProducto(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $styleArrayTitulo = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => array(
                'name'      =>  'Calibri',
                'color' => ['argb' => '000000'],
                'bold' => true
            )
        ];
        $colorFondoCabecera = "F7CAAC";
        $sheet = $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $sheet = $spreadsheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
        $sheet = $spreadsheet->getActiveSheet();
        $TituloCabecera = [
            'CODALT',
            'PRODUCTO',
            'MARCA',
            'FORMATO',
            'SABOR',
            'CAJA',
            'PAQUETE',
            'CAJAXPAQUETE',
            'CODIGO PADRE',
            'CODIGO HIJO',
            'LINEA'
        ];
        $data1 = Producto::ProductoListar($request);
        $fila = 1;
        foreach ($TituloCabecera as $i => $d) {
            $sheet->setCellValue(Excel::Columnas[$i] . $fila, ucwords($TituloCabecera[$i]))->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
            $valor = Excel::Columnas[$i] . $fila;
            $sheet->getStyle($valor)->applyFromArray($styleArrayTitulo);
            $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($valor)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
            $sheet->getStyle($valor)->getFont()->setBold(true);
        }
        $fila++;
        $arrayData1 = [
            'sku',
            'nombreProducto',
            'marca',
            'formato',
            'sabor',
            'caja',
            'paquete',
            'cajaxpaquete',
            'codigoPadre',
            'codigoHijo',
            'nombreLinea',
        ];
        if (count($data1) > 0) {
            for ($x = 0; $x < count($data1); $x++) {
                for ($i = 0; $i < count($arrayData1); $i++) {
                    $nombreKey = $arrayData1[$i];
                    $sheet->setCellValue(Excel::Columnas[$i] . $fila, $data1[$x]->$nombreKey)->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
                    $valor = Excel::Columnas[$i] . $fila;
                    $sheet->getStyle($valor)->applyFromArray($styleArray);
                    $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
                }
                $fila++;
            }
        } else {
            $sheet->mergeCells('A' . $fila . ':K' . $fila)->setCellValue('A' . $fila, "NO HAY DATA QUE MOSTRAR");
            $sheet->getStyle('A' . $fila . ':K' . $fila . '')->applyFromArray($styleArray);
            $sheet->getStyle('A' . $fila)->getAlignment()->setHorizontal('center');
        }
        return $spreadsheet;
    }
    public static function GenerarExcelComision(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $styleArrayTitulo = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => array(
                'name'      =>  'Calibri',
                'color' => ['argb' => '000000'],
                'bold' => true
            )
        ];
        $colorFondoCabecera = "F7CAAC";
        $sheet = $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $sheet = $spreadsheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
        $sheet = $spreadsheet->getActiveSheet();
        $TituloCabecera = [
            'CODIGO PADRE',
            'PRODUCTO',
            'CONDICION',
            'VALOR',
            'COM.PVTA',
            'COM.DIST'
        ];
        $data1 = ComisionDetalle::ComisionDetalleListarExcel($request);
        $fila = 1;
        foreach ($TituloCabecera as $i => $d) {
            $sheet->setCellValue(Excel::Columnas[$i] . $fila, ucwords($TituloCabecera[$i]))->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
            $valor = Excel::Columnas[$i] . $fila;
            $sheet->getStyle($valor)->applyFromArray($styleArrayTitulo);
            $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($valor)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondoCabecera);
            $sheet->getStyle($valor)->getFont()->setBold(true);
        }
        $fila++;
        $arrayData1 = [
            'codigoPadre',
            'nombreProducto',
            'condicion',
            'cantidadValor',
            'comisionPtoVenta',
            'comisionDistribuidor',
        ];
        if (count($data1) > 0) {
            for ($x = 0; $x < count($data1); $x++) {
                for ($i = 0; $i < count($arrayData1); $i++) {
                    $nombreKey = $arrayData1[$i];
                    $sheet->setCellValue(Excel::Columnas[$i] . $fila, $data1[$x]->$nombreKey)->getColumnDimension(Excel::Columnas[$i])->setAutoSize(true);
                    $valor = Excel::Columnas[$i] . $fila;
                    $sheet->getStyle($valor)->applyFromArray($styleArray);
                    $sheet->getStyle($valor)->getAlignment()->setHorizontal('center');
                }
                $fila++;
            }
        } else {
            $sheet->mergeCells('A' . $fila . ':G' . $fila)->setCellValue('A' . $fila, "NO HAY DATA QUE MOSTRAR");
            $sheet->getStyle('A' . $fila . ':G' . $fila . '')->applyFromArray($styleArray);
            $sheet->getStyle('A' . $fila)->getAlignment()->setHorizontal('center');
        }
        return $spreadsheet;
    }
    #endregion
    #region Metodos Convertidor Archivo
    public static function CopiarArchivosTmp($archivo)
    {
        $rutaDirectorio = public_path('Excels');
        $nombre = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $archivo->getClientOriginalExtension();
        $ArchivoNombre = $nombre . "_" . time() . "." . $extension;
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
            "I" => "SKU",
            "J" => "MARCAS",
            "K" => "SUPERVISOR",
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
            'C' => 'MARCA',
            'D' => 'FORMATO',
            'E' => 'COD',
            'F' => 'Caja X',
            'H' => 'SKU',
            'I' => 'Bonif (Botellas)',
            'K' => 'SABOR A BONIFICAR',
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
    public static function ProcesarDataImportadaPedidoExcel(
        $DataTB_UNI,
        $DataPEDIDO,
        $DataBONIFICACIONES,
        $DataGGVVRUTA,
        $DataCLIENTEPEDIDOProcesada
    ) {
        $DataPedidogroupBy = $DataPEDIDO->groupBy('NROPEDIDO');
        $nuevaData = [];
        foreach ($DataPedidogroupBy as $dpg) {
            $CodigosCODALT = [];
            $CodigosCOD = [];
            $CodigosIndependienteCOD = [];

            $listaCODALTProducto = $dpg->map(function ($item, $key) {
                return $item['CODALT'];
            });
            $listaCODALTProductoNueva = [];
            foreach ($listaCODALTProducto as $lp) {
                $listaCODALTProductoNueva[] = $lp;
            }
            // $listaLineaProducto = $DataTB_UNI->whereIn('CODALT', $listaCODALTProductoNueva);
            $objCliente = $DataCLIENTEPEDIDOProcesada->where('NROPEDIDO', $dpg[0]['NROPEDIDO'])->first();
            $lineasProducto = [];
            if ($objCliente != null) {
                $listaLineaProducto = $DataGGVVRUTA
                    ->where('RUTA', $objCliente['RUTA'])
                    ->whereIn('SKU', $listaCODALTProductoNueva);

                if ($listaLineaProducto !== null) {
                    foreach ($listaLineaProducto as $lcp) {
                        $lineasProducto[]  = $lcp['LINEA'];
                    }
                }

                $lineasProducto = array_unique($lineasProducto);
            }


            foreach ($dpg as $key => $dp) {
                $CodigosCODALT[] = $dp['CODALT'];
                $objPlantilla = $DataTB_UNI->where('CODALT', $dp['CODALT'])->first();
                $obBonificaciones = $DataBONIFICACIONES->where('SKU', $dp['CODALT'])->first();
                $codigoIndp = $obBonificaciones != null ? $obBonificaciones['COD'] : '';
                if ($codigoIndp != "") {
                    $CodigosIndependienteCOD[] = $codigoIndp;
                }
                $CodigosCOD[] = [
                    'COD' => $obBonificaciones != null ? $obBonificaciones['COD'] : '',
                    'Caja X' => $obBonificaciones != null ? $obBonificaciones['Caja X'] : '',
                    'Boni' => $obBonificaciones != null ? $obBonificaciones['Bonif (Botellas)'] : '',
                    'MARCA' => $obBonificaciones != null ? $obBonificaciones['MARCA'] : '',
                    'FORMATO' => $obBonificaciones != null ? $obBonificaciones['FORMATO'] : '',
                    'SABOR A BONIFICAR' => $obBonificaciones != null ? $obBonificaciones['SABOR A BONIFICAR'] : '',
                    'CantidadPedido' => $dp['CANTIDAD'],
                    'CODALT' => $dp['CODALT'],
                    'NROPEDIDO' => $dp['NROPEDIDO'],
                    'FEPVTA' => $dp['FEPVTA'],
                    'FEMOVI' => $dp['FEMOVI'],
                ];
                $nroPedidoEspejo = $dp['NROPEDIDO'];

                if (count($lineasProducto) > 1) {
                    $objGGVVRUTA = $DataGGVVRUTA
                        ->where('SKU', $dp['CODALT'])
                        ->where('RUTA', $objCliente['RUTA'])
                        ->first();
                    if ($objGGVVRUTA != null) {
                        if ($objGGVVRUTA['LINEA'] == 2) {
                            $nroPedidoEspejo = $dp['NROPEDIDO'] . 'e';
                        }
                    }
                }
                $nuevaData[] = [
                    'NROPEDIDO' => $nroPedidoEspejo,
                    'FEPVTA' => $dp['FEPVTA'],
                    'FEMOVI' => $dp['FEMOVI'],
                    'CODALT' => $dp['CODALT'],
                    'CANTIDAD' => $objPlantilla != null ? Excel::CalcularCantidadPaqueteProducto($objPlantilla['PAQUETE'], $dp['CANTIDAD']) : '',
                    'PRECIO' => $dp['PRECIO'],
                    'PDSCTO' => $dp['PDSCTO'],
                    'DESCTO' => $dp['DESCTO'],
                    'TDOCTO' => $dp['TDOCTO'],
                ];
            }
            // Obtiene los n productos en bonificaciones
            // $ProductosBonificaciones = $DataBONIFICACIONES->whereIn('SKU', $CodigosCODALT)->groupBy('COD');
            $ProductosBonificaciones = $DataBONIFICACIONES->whereIn('SKU', $CodigosCODALT)->groupBy(['MARCA', 'FORMATO']);
            if (count($CodigosIndependienteCOD) > 0) {
                $CodigosCOD = collect($CodigosCOD)->whereIn('COD', $CodigosIndependienteCOD);
            } else {
                $CodigosCOD = collect([]);
            }

            foreach ($ProductosBonificaciones as $boni) {
                // Cada objeto es son los items del pedido
                $cantidadProductosHijos = $boni;
                $cantidadCajaX = 0;
                $boniCaja = 0;
                $NROPEDIDO = 0;
                $FEPVTA = 0;
                $FEMOVI = 0;
                $CODALT = 0;
                $sumaCantidades = 0;
                foreach ($cantidadProductosHijos as $cph) {
                    foreach ($cph as $pp) {
                        $midata = $CodigosCOD->where('MARCA', $pp['MARCA'])->where('FORMATO', $pp['FORMATO'])->all();
                        $sumaCantidades = 0;
                        foreach ($midata as $lp) {
                            $sumaCantidades += $lp['CantidadPedido'];
                            $cantidadCajaX = $lp['Caja X'];
                            $boniCaja =  $lp['Boni'];
                            $NROPEDIDO = $lp['NROPEDIDO'];
                            $FEPVTA = $lp['FEPVTA'];
                            $FEMOVI = $lp['FEMOVI'];
                            $CODALT = $lp['SABOR A BONIFICAR'];
                        }
                    }
                    $numeroBonificaciones = 0;
                    if ($cantidadCajaX > 0) {
                        $numeroBonificaciones = Excel::CalcularBonificacionProducto($cantidadCajaX, $sumaCantidades);
                    }
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
                            'TDOCTO' => 218,
                        ];
                    }
                }
            }
        }
        return collect($nuevaData);
    }
    public static function ProcesarDataImportadaClientePedidoExcel(
        $DataGGVVRUTA,
        $DataCLIENTEPEDIDO,
        $DataDATA_CLI,
        $DataImportarDataPEDIDO
    ) {
        $nuevaData = [];
        foreach ($DataCLIENTEPEDIDO as $cp) {
            //Ruta y Modulo están
            $Cli = $DataDATA_CLI->where('DNI', $cp['NRO_DOC'])->first();
            $nuevaDataImportarDataPEDIDO = $DataImportarDataPEDIDO->where('NROPEDIDO', $cp['NROPEDIDO']);
            $listaCodigoProducto = $nuevaDataImportarDataPEDIDO->map(function ($item, $key) {
                return $item['CODALT'];
            });

            $listaPDV = [];
            if ($Cli != null) {
                $nuevaDataGGVVRUTA = $DataGGVVRUTA->whereIn('SKU', $listaCodigoProducto)->where('RUTA', $Cli['RUTA']);
                $listaPDV = $nuevaDataGGVVRUTA->map(function ($item, $key) {
                    return $item['CODIGO'];
                })->unique();
            }

            $tipoDocumento = strlen($cp['NRO_DOC']) <= 8 ? 0 : 1;
            $nroDocumento = $tipoDocumento == 1 ? Excel::CompletadorCerosDNI($cp['NRO_DOC']) : $cp['NRO_DOC'];
            $listaPDVNueva = [];
            foreach ($listaPDV as $pdv) {
                $listaPDVNueva[] = $pdv;
            }
            if (count($listaPDVNueva) > 0) {
                foreach ($listaPDVNueva as $key => $pdv) {
                    $nuevaData[] = [
                        'NROPEDIDO' => $key > 0 ? $cp['NROPEDIDO'] . 'e' : $cp['NROPEDIDO'],
                        'COD_CLIE' => $cp['COD_CLIE'],
                        'CLIENTE' => $cp['CLIENTE'],
                        'DIRECCION' => $cp['DIRECCION'],
                        'TIPO_DOC' => $tipoDocumento,
                        'NRO_DOC' => $nroDocumento,
                        'RUTA' => $Cli == null ? '' : $Cli['RUTA'],
                        'MODULO' => $Cli == null ? '' : $Cli['MODULO'],
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
                        'PDV' => $pdv
                    ];
                }
            } else {
                $nuevaData[] = [
                    'NROPEDIDO' => $cp['NROPEDIDO'],
                    'COD_CLIE' => $cp['COD_CLIE'],
                    'CLIENTE' => $cp['CLIENTE'],
                    'DIRECCION' => $cp['DIRECCION'],
                    'TIPO_DOC' => $tipoDocumento,
                    'NRO_DOC' => $nroDocumento,
                    'RUTA' => $Cli == null ? '' : $Cli['RUTA'],
                    'MODULO' => $Cli == null ? '' : $Cli['MODULO'],
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
                    'PDV' => ''
                ];
            }
        }
        return collect($nuevaData);
    }
    public static function CalcularCantidadPaqueteProducto($CantidadPaquete, $CantidadUnidad)
    {
        $calculado = $CantidadUnidad / $CantidadPaquete;
        return $calculado;
    }
    public static function GenerarExcelPedidoCorregido(
        $DataTB_UNI,
        $DataGGVVRUTA,
        $DataCLIENTEPEDIDO,
        $DataBONIFICACIONES,
        $DataPEDIDO,
        $DataDATA_CLI
    ) {
        $DataCLIENTEPEDIDOProcesada = Excel::ProcesarDataImportadaClientePedidoExcel(
            $DataGGVVRUTA,
            $DataCLIENTEPEDIDO,
            $DataDATA_CLI,
            $DataPEDIDO,
        );

        $DataPEDIDOProcesada = Excel::ProcesarDataImportadaPedidoExcel(
            $DataTB_UNI,
            $DataPEDIDO,
            $DataBONIFICACIONES,
            $DataGGVVRUTA,
            $DataCLIENTEPEDIDOProcesada
        );

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
    #endregion
}
