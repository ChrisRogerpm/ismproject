<?php

namespace App\Model;

use Carbon\Carbon;
use App\Utilitarios\Excel;
use App\Model\Bonificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = "pedido";
    protected $primaryKey = "idPedido";
    protected $fillable = [
        'idCeo',
        'nroDocumentoCliente',
        'nroPedido',
        'visita',
        'latitud',
        'longitud',
        'giro',
        'email',
        'canal',
        'lprecio',
        'idGestor',
    ];
    public $timestamps = false;
    public static function PedidoListar(Request $request)
    {
        $idCeo = $request->input('idCeo');
        return DB::select(DB::raw("SELECT
            p.idPedido,
            p.nroPedido,
            c.codigoCliente,
            c.nombreRazonSocial,
            c.direccion,
            c.referencia,
            IF(IF(CHAR_LENGTH(c.nroDocumento) <= 8,0,1) = 1,'RUC','DNI') AS tipoDocumento,
            c.nroDocumento,
            c.ruta,
            c.modulo,
            p.visita,
            p.latitud,
            p.longitud,
            p.giro,
            p.email,
            c.telefono,
            co.lugar,
            co.codigoCeo,
            p.canal,
            p.lprecio,
            CONCAT(g.codigoGestor,' - ',g.nombre) AS nombreGestor
        FROM pedido AS p
        INNER JOIN cliente AS c ON c.nroDocumento = p.nroDocumentoCliente
        INNER JOIN centrooperativo AS co ON co.idCeo = p.idCeo
        INNER JOIN gestor as g ON g.idGestor = p.idGestor
        WHERE p.idCeo = $idCeo"));
    }
    public static function PedidoRegistrar(Request $request)
    {
        $data = new Pedido();
        $data->idCeo = $request->input('idCeo');
        $data->nroDocumentoCliente = $request->input('nroDocumentoCliente');
        $data->nroPedido = $request->input('nroPedido');
        $data->visita = $request->input('visita');
        $data->latitud = $request->input('latitud');
        $data->longitud = $request->input('longitud');
        $data->giro = $request->input('giro');
        $data->email = $request->input('email');
        $data->canal = $request->input('canal');
        $data->lprecio = $request->input('lprecio');
        $data->idGestor = $request->input('idGestor');
        $data->save();
        return $data;
    }
    public static function PedidoImportarClienteDataExcel(Request $request)
    {
        $archivoPedido = $request->file('archivoPedido');
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
    public static function PedidoImportarPedidoDataExcel(Request $request)
    {
        $archivoPedido = $request->file('archivoPedido');
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
    public static function PedidoImportarData(Request $request)
    {
        $DataTB_UNI = collect(Producto::ProductoListarActivos($request));
        $DataGGVVRUTA = collect(Gestor::GestorListarData($request));
        $DataBONIFICACIONES = collect(Bonificacion::BonificacionListarProcesado($request));
        $DataDATA_CLI = collect(Cliente::ClienteListar($request));
        $DataCLIENTEPEDIDO = Pedido::PedidoImportarClienteDataExcel($request);
        $DataPEDIDO = Pedido::PedidoImportarPedidoDataExcel($request);
        $CentroOperativo = CentroOperativo::findOrfail($request->input('idCeo'));

        $Cliente = Pedido::PedidoProcesarPedidoCliente($DataGGVVRUTA, $DataCLIENTEPEDIDO, $DataDATA_CLI, $DataPEDIDO, $CentroOperativo);
        $Pedido = Pedido::PedidoProcesarPedido($DataTB_UNI, $DataPEDIDO, $DataBONIFICACIONES, $DataGGVVRUTA, $Cliente, $CentroOperativo);
        return Excel::GenerarExcelPedidoProcesado($Cliente, $Pedido);
    }
    public static function PedidoProcesarPedidoCliente($DataGGVVRUTA, $DataCLIENTEPEDIDO, $DataDATA_CLI, $DataPEDIDO, $CentroOperativo)
    {
        $nuevaData = [];
        foreach ($DataCLIENTEPEDIDO as $cp) {
            //Ruta y Modulo están
            $Cli = $DataDATA_CLI->where('nroDocumento', $cp['NRO_DOC'])->first();
            $nuevaDataImportarDataPEDIDO = $DataPEDIDO->where('NROPEDIDO', $cp['NROPEDIDO']);
            $listaCodigoProducto = $nuevaDataImportarDataPEDIDO->map(function ($item, $key) {
                return $item['CODALT'];
            });
            $listaPDV = [];
            if ($Cli != null) {
                $nuevaDataGGVVRUTA = $DataGGVVRUTA->whereIn('sku', $listaCodigoProducto)->where('ruta', $Cli->ruta);
                $listaPDV = $nuevaDataGGVVRUTA->map(function ($item, $key) {
                    return trim($item['codigo']);
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
                        'RUTA' => $Cli == null ? '' : $Cli->ruta,
                        'MODULO' => $Cli == null ? '' : $Cli->modulo,
                        'VISITA' => $cp['VISITA'],
                        'LATITUD' => $cp['LATITUD'],
                        'LONGITUD' => $cp['LONGITUD'],
                        'GIRO' => $cp['GIRO'],
                        'EMAIL' => $cp['EMAIL'],
                        'TELEFONO' => $cp['TELEFONO'],
                        'LUGAR' => $CentroOperativo != null ? $CentroOperativo->lugar : '',
                        'SUCURSAL' => $CentroOperativo != null ? $CentroOperativo->codigoCeo : '',
                        'CANAL' => $cp['CANAL'],
                        'REFERENCIA' => $cp['REFERENCIA'],
                        'LPRECIO' => $cp['LPRECIO'],
                        'PDV' => $pdv
                    ];
                    $dataGestor = Gestor::where('codigoGestor', $pdv)->first();

                    $validar = Pedido::where('nroPedido', trim($cp['NROPEDIDO']))->first();
                    if ($validar == null) {
                        Pedido::PedidoRegistrar(new Request([
                            'idCeo' => $CentroOperativo->idCeo,
                            'nroDocumentoCliente' => $nroDocumento,
                            'nroPedido' => $key > 0 ? $cp['NROPEDIDO'] . 'e' : $cp['NROPEDIDO'],
                            'visita' => $cp['VISITA'],
                            'latitud' => $cp['LATITUD'],
                            'longitud' => $cp['LONGITUD'],
                            'giro' => $cp['GIRO'],
                            'email' => $cp['EMAIL'],
                            'canal' => $cp['CANAL'],
                            'lprecio' => $cp['LPRECIO'],
                            'idGestor' => $dataGestor->idGestor,
                        ]));
                    }
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
                    'LUGAR' => $CentroOperativo != null ? $CentroOperativo->lugar : '',
                    'SUCURSAL' => $CentroOperativo != null ? $CentroOperativo->codigoCeo : '',
                    'CANAL' => $cp['CANAL'],
                    'REFERENCIA' => $cp['REFERENCIA'],
                    'LPRECIO' => $cp['LPRECIO'],
                    'PDV' => ''
                ];
                $validar = Pedido::where('nroPedido', trim($cp['NROPEDIDO']))->first();
                if ($validar == null) {
                    Pedido::PedidoRegistrar(new Request([
                        'idCeo' => $CentroOperativo->idCeo,
                        'nroDocumentoCliente' => $nroDocumento,
                        'nroPedido' => $cp['NROPEDIDO'],
                        'visita' => $cp['VISITA'],
                        'latitud' => $cp['LATITUD'],
                        'longitud' => $cp['LONGITUD'],
                        'giro' => $cp['GIRO'],
                        'email' => $cp['EMAIL'],
                        'canal' => $cp['CANAL'],
                        'lprecio' => $cp['LPRECIO'],
                        'idGestor' => null,
                    ]));
                }
            }
        }
        return collect($nuevaData);
    }
    public static function PedidoProcesarPedido($DataTB_UNI, $DataPEDIDO, $DataBONIFICACIONES, $DataGGVVRUTA, $DataCLIENTEPEDIDOProcesada, $CentroOperativo)
    {
        $DataPedidogroupBy = $DataPEDIDO->groupBy('NROPEDIDO');
        $nuevaData = [];
        $nuevaDataSinConvertir = [];
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
            $objCliente = $DataCLIENTEPEDIDOProcesada->where('NROPEDIDO', $dpg[0]['NROPEDIDO'])->first();
            $lineasProducto = [];
            if ($objCliente != null) {
                $listaLineaProducto = $DataGGVVRUTA
                    ->where('ruta', $objCliente['RUTA'])
                    ->whereIn('sku', $listaCODALTProductoNueva);

                if ($listaLineaProducto !== null) {
                    foreach ($listaLineaProducto as $lcp) {
                        $lineasProducto[]  = $lcp['linea'];
                    }
                }
                $lineasProducto = array_unique($lineasProducto);
            }
            foreach ($dpg as $key => $dp) {
                $CodigosCODALT[] = $dp['CODALT'];
                $objPlantilla = $DataTB_UNI->where('sku', $dp['CODALT'])->first();
                $obBonificaciones = $DataBONIFICACIONES->where('sku', $dp['CODALT'])->first();
                $codigoIndp = $obBonificaciones != null ? $obBonificaciones->codigoPadre : '';
                if ($codigoIndp != "") {
                    $CodigosIndependienteCOD[] = $codigoIndp;
                }
                $CodigosCOD[] = [
                    'COD' => $obBonificaciones != null ? $obBonificaciones->codigoPadre : '',
                    'Caja X' => $obBonificaciones != null ? $obBonificaciones->cajaX : '',
                    'Boni' => $obBonificaciones != null ? $obBonificaciones->nroBotellasBonificar : '',
                    'MARCA' => $obBonificaciones != null ? $obBonificaciones->marca : '',
                    'FORMATO' => $obBonificaciones != null ? $obBonificaciones->formato : '',
                    'SABOR A BONIFICAR' => $obBonificaciones != null ? $obBonificaciones->saborBonificar : '',
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
                    'CANTIDAD' => $objPlantilla != null ? Excel::CalcularCantidadPaqueteProducto($objPlantilla->paquete, $dp['CANTIDAD']) : '',
                    'PRECIO' => $dp['PRECIO'],
                    'PDSCTO' => $dp['PDSCTO'],
                    'DESCTO' => $dp['DESCTO'],
                    'TDOCTO' => $dp['TDOCTO'],
                ];
                $nuevaDataSinConvertir[] = [
                    'NROPEDIDO' => $nroPedidoEspejo,
                    'FEPVTA' => $dp['FEPVTA'],
                    'FEMOVI' => $dp['FEMOVI'],
                    'CODALT' => $dp['CODALT'],
                    'CANTIDAD' => $dp['CANTIDAD'],
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
                            'TDOCTO' => 207,
                        ];
                        $nuevaDataSinConvertir[] = [
                            'NROPEDIDO' => $NROPEDIDO,
                            'FEPVTA' => $FEPVTA,
                            'FEMOVI' => $FEMOVI,
                            'CODALT' => $CODALT,
                            'CANTIDAD' => $sumaCantidades,
                            'PRECIO' => 0,
                            'PDSCTO' => 0,
                            'DESCTO' => 0,
                            'TDOCTO' => 207,
                        ];
                    }
                }
            }
        }
        $ListaNroPedidos = collect($nuevaDataSinConvertir)->groupBy('NROPEDIDO')->keys();
        foreach ($ListaNroPedidos as $nroPedido) {
            $objPedidoDetalle = PedidoDetalle::where('nroPedido', $nroPedido)->where('idCeo', $CentroOperativo->idCeo)->first();
            if ($objPedidoDetalle == null) {
                $items = collect($nuevaDataSinConvertir)->where('NROPEDIDO', $nroPedido);
                foreach ($items as $item) {
                    $objProducto = Producto::where('sku', $item['CODALT'])->where('idCeo', $CentroOperativo->idCeo)->first();
                    PedidoDetalle::PedidoDetalleRegistrar(new Request([
                        'idCeo' =>  $CentroOperativo->idCeo,
                        'nroPedido' => $item['NROPEDIDO'],
                        'fechaVenta' => Carbon::createFromFormat('d/m/Y', explode(" ", $item['FEPVTA'])[0])->format('Y-m-d'),
                        'fechaMovimiento' => Carbon::createFromFormat('d/m/Y', explode(" ", $item['FEMOVI'])[0])->format('Y-m-d'),
                        'idProducto' => $objProducto->idProducto,
                        'cantidad' => $item['CANTIDAD'],
                        'precio' => $item['PRECIO'],
                        'precioDescuento' => $item['PDSCTO'],
                        'descuento' => $item['DESCTO'],
                        'tdocto' => $item['TDOCTO'],
                    ]));
                }
            }
        }
        return collect($nuevaData);
    }
}
