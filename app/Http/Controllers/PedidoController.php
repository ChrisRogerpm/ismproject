<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Pedido;
use App\Model\PedidoDetalle;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    #region Vista
    public function PedidoListarVista()
    {
        return view('Pedido.PedidoListar');
    }
    #endregion
    #region JSON
    public function PedidoListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Pedido::PedidoListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function PedidoDetalleListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = PedidoDetalle::PedidoDetalleListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function PedidoImportarDataJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        $data = "";
        try {
            $data = Pedido::PedidoImportarData($request);
            $respuesta = true;
            $mensaje = "Se ha importado exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje, 'data' => $data]);
    }
    #endregion
}
