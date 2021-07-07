<?php

namespace App\Http\Controllers;

use Exception;
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
    #endregion
    #region JSON
    public function ReportImportarExcelsJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        $data = "";
        $msj = "";
        try {
            $data = Excel::GenerarExcelPedidoCorregido($request);
            $respuesta = true;
            $mensaje = "Se ha importado exitosamente";
        } catch (Exception $ex) {
            $msj = $ex->getMessage();
            $mensaje = "Ah ocurrido un error, revíse los archivos e intente nuevamente";
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje, 'data' => $data, 'msj' => $msj]);
    }
    #endregion
}
