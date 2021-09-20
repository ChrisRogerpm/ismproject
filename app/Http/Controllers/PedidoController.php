<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Pedido;
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
    #endregion
}
