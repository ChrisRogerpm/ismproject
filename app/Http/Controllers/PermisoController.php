<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    #region JSON
    public function PermisoListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data = Permiso::PermisoListar();
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    #endregion
}
