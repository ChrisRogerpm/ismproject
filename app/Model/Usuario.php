<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $table = "usuario";
    protected $primaryKey = "idUsuario";
    protected $fillable = [
        'idCeo',
        'idRol',
        'nombre',
        'apellido',
        'nombreApellido',
        'nroDocumento',
        'email',
        'password',
        'fechaRegistro',
        'estado',
    ];
    public $timestamps = false;

    public static function UsuarioListar(Request $request)
    {
        return DB::select(DB::raw("SELECT
            u.idUsuario,
            ceo.nombreCeo,
            r.nombreRol,
            UPPER(u.nombreApellido) AS nombreApellido,
            u.nroDocumento,
            u.email,
            IF(u.estado = 1,'ACTIVO','INACTIVO') AS estado,
            u.estado as idestado
        FROM usuario AS u
        INNER JOIN centrooperativo AS ceo ON ceo.idCeo = u.idCeo
        INNER JOIN rol AS r ON r.idRol = u.idRol
        WHERE u.idCeo != 0"));
    }

    public static function UsuarioRegistrar(Request $request)
    {
        $data = new Usuario();
        $data->idCeo = $request->input('idCeo');
        $data->idRol = $request->input('idRol');
        $data->nombre = $request->input('nombre');
        $data->apellido = $request->input('apellido');
        $data->nombreApellido = $request->input('nombre') . ' ' . $request->input('apellido');
        $data->nroDocumento = $request->input('nroDocumento');
        $data->email = $request->input('email');
        $data->password = bcrypt($request->input('nroDocumento'));
        $data->fechaRegistro = Carbon::now()->toDateString();
        $data->estado = 1;
        $data->save();
        return $data;
    }

    public static function UsuarioEditar(Request $request)
    {
        $data = Usuario::findOrfail($request->input('idUsuario'));
        $data->idRol = $request->input('idRol');
        $data->nombre = $request->input('nombre');
        $data->apellido = $request->input('apellido');
        $data->nombreApellido = $request->input('nombre') . ' ' . $request->input('apellido');
        $data->nroDocumento = $request->input('nroDocumento');
        $data->email = $request->input('email');
        $data->save();
        return $data;
    }
    public static function UsuarioBloquear(Request $request)
    {
        $data = Usuario::findOrfail($request->input('idUsuario'));
        $data->estado = 0;
        $data->save();
    }
    public static function UsuarioRestablecer(Request $request)
    {
        $data = Usuario::findOrfail($request->input('idUsuario'));
        $data->estado = 1;
        $data->save();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
