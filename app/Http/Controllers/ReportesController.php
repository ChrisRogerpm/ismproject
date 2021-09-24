<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Reporte;
use App\Utilitarios\Excel;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    #region Vista
    public function ReportePedidosListarVista()
    {
        $archivos = glob("Excels/*");
        foreach ($archivos as $file) {
            if (is_file($file)) unlink($file);
        }
        return view('Reportes.ReportePedidos');
    }
    public function ReporteProductoMasVendidoVista()
    {
        return view('Reportes.ReporteProducto');
    }
    public function ReporteNroPedidoMasVendidoVista()
    {
        return view('Reportes.ReporteNroPedido');
    }
    #endregion
    #region JSON
    public function ReportImportarExcelsJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        $data = "";
        $msj = "";
        try {
            $request->request->add(['archivoPlantilla' => Excel::CopiarArchivosTmp($request->file('archivoPlantilla'))]);
            $request->request->add(['archivoPedido' => Excel::CopiarArchivosTmp($request->file('archivoPedido'))]);
            $request->request->add(['archivoBonificaciones' => Excel::CopiarArchivosTmp($request->file('archivoBonificaciones'))]);

            $DataTB_UNI = Excel::ImportarDataTB_UNI($request);
            $DataGGVVRUTA = Excel::ImportarDataGGVVRUTA($request);
            $DataCLIENTEPEDIDO = Excel::ImportarDataCLIENTEPEDIDO($request);
            $DataBONIFICACIONES = Excel::ImportarDataBONIFICACIONES($request);
            $DataPEDIDO = Excel::ImportarDataPEDIDO($request);
            $DataDATA_CLI = Excel::ImportarDataDATA_CLI($request);

            $data = Excel::GenerarExcelPedidoCorregido(
                $DataTB_UNI,
                $DataGGVVRUTA,
                $DataCLIENTEPEDIDO,
                $DataBONIFICACIONES,
                $DataPEDIDO,
                $DataDATA_CLI
            );
            $respuesta = true;
            $mensaje = "Se ha importado exitosamente";
        } catch (Exception $ex) {
            $msj = $ex->getMessage();
            $mensaje = "Ah ocurrido un error, revíse los archivos e intente nuevamente";
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje, 'data' => $data, 'msj' => $msj]);
    }
    public function ReporteProductoListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Reporte::ReporteProductoListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function ReporteNroPedidoListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Reporte::ReporteNroPedidoListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    #endregion
}
