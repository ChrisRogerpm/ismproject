<?php

namespace App\Http\Controllers;

use App\Utilitarios\Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends Controller
{
    public function GestoresDownload(Request $request)
    {
        $randomTime = time();
        $spreadsheet = Excel::GenerarExcelGestor($request);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ListaGestoresCompacto_' . $randomTime . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function GestorExcelDownload(Request $request)
    {
        $randomTime = time();
        $spreadsheet = Excel::GenerarExcelGestorExcel($request);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ListaGestoresDetalle_' . $randomTime . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function BonificacionesDownload(Request $request)
    {
        $randomTime = time();
        $spreadsheet = Excel::GenerarExcelBonificacion($request);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Bonificaciones_' . $randomTime . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function PedidoMasVendidoDownload(Request $request)
    {
        $randomTime = time();
        $spreadsheet = Excel::GenerarExcelReportePedido($request);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="PedidoMasVendido_' . $randomTime . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function ProductoDownload(Request $request)
    {
        $randomTime = time();
        $spreadsheet = Excel::GenerarExcelProducto($request);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Productos_' . $randomTime . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    // GenerarExcelBonificacion
}
