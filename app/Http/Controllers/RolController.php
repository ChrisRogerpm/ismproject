<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Rol;
use App\Model\RolPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    #region Vista
    public function RolListarVista()
    {
        return view('Rol.RolListar');
    }
    public function RolRegistrarVista()
    {
        return view('Rol.RolRegistrar');
    }
    public function RolEditarVista($idRol)
    {
        $Rol = Rol::findOrfail($idRol);
        $ListaRolPermiso = RolPermiso::where('idRol', $Rol->idRol)->get();
        return view('Rol.RolEditar', compact('Rol', 'ListaRolPermiso'));
    }
    #endregion
    #region JSON
    public function RolListarJson(Request $request)
    {
        $data = "";
        $mensaje = "";
        try {
            $data  = Rol::RolListar($request);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['data' => $data, 'mensaje' => $mensaje]);
    }
    public function RolRegistrarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            $data = Rol::RolRegistrar($request);
            $ListadePermisosTabla = $request->input('ListadePermisosTabla');
            foreach ($ListadePermisosTabla as $lista) {
                $req = new Request([
                    'idRol' => $data->idRol,
                    'permisoModulo' => $lista,
                ]);
                RolPermiso::RolPermisoRegistrar($req);
            }
            $respuesta = true;
            $mensaje = "Se ha registrado el Rol exitosamente";
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function RolEditarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            Rol::RolEditar($request);
            $ListadePermisosTabla = $request->input('ListadePermisosTabla');
            foreach ($ListadePermisosTabla as $lista) {
                $objRolPermiso = RolPermiso::where('idRol', $request->input('idRol'))->where('permisoModulo', $lista)->first();
                if ($objRolPermiso == null) {
                    $req = new Request([
                        'idRol' => $request->input('idRol'),
                        'permisoModulo' => $lista,
                    ]);
                    RolPermiso::RolPermisoRegistrar($req);
                }
            }
            //Eliminar los permisos no chequeados
            RolPermiso::whereNotIn('permisoModulo', $ListadePermisosTabla)->delete();
            //
            $respuesta = true;
            $mensaje = "Se ha editado el Rol exitosamente";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    public function RolEliminarJson(Request $request)
    {
        $respuesta = false;
        $mensaje = "";
        try {
            $ValidarUsabilidadRoles = Rol::RolValidarUsabilidadRoles($request);
            if ($ValidarUsabilidadRoles->respuesta) {
                $mensaje = "Se ha encontrado que los roles " . implode($ValidarUsabilidadRoles->Lista) . " están siendo usados.";
            } else {
                Rol::RolEliminar($request);
                $respuesta = true;
                $mensaje = "Se ha eliminado los Roles exitosamente";
            }
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        return response()->json(['respuesta' => $respuesta, 'mensaje' => $mensaje]);
    }
    #endregion
}
