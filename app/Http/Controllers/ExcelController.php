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
        header('Content-Disposition: attachment;filename="ListaGestoresCompacto' . $randomTime . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function GestorExcelDownload(Request $request)
    {
        $randomTime = time();
        $spreadsheet = Excel::GenerarExcelGestorExcel($request);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ListaGestoresDetalle' . $randomTime . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function BonificacionesDownload(Request $request)
    {
        $randomTime = time();
        $spreadsheet = Excel::GenerarExcelBonificacion($request);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Bonificaciones' . $randomTime . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    // GenerarExcelBonificacion
}
